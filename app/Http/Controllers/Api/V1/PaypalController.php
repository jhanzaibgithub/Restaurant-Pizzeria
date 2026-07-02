<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Exception;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use App\CentralLogics\Helpers;
use Illuminate\Support\Str;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use Illuminate\Http\Response;
use PayPal\Api\PaymentExecution;
use App\Http\Controllers\Controller;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Model\Order;
use DB;
use Illuminate\Support\Facades\Http;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $mode = env('APP_MODE');
        $paypal = Helpers::get_business_settings('paypal');

        if ($paypal) {
            $paypal_mode = ($mode == 'live') ? 'live' : 'sandbox';

            $config = [
                'client_id' => $paypal['paypal_client_id'],
                'secret' => $paypal['paypal_secret'],
                'settings' => [
                    'mode' => env('PAYPAL_MODE', $paypal_mode),
                    'http.ConnectionTimeOut' => 30,
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path() . '/logs/paypal.log',
                    'log.LogLevel' => 'ERROR'
                ],
            ];

            Config::set('paypal', $config);
        }

        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * Initiate the PayPal payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function payWithpaypal(Request $request)
    {	
	   	$order = Order::findorFail($request->input('order_id'));
	   	
        $order_amount = $order->order_amount;
        $order_id = $request->input('order_id');
        $customer = User::where('id', $order->user_id)->first();

        if(is_null($order_id)){
             return response()->json(['error' => 'order id not found']);
        }

        if(is_null($customer)){
            return response()->json(['error' => 'user not found']);
        }

        $callback = $request->input('callback');
		info($callback);
        $tr_ref = Str::random(6) . '-' . rand(1, 1000);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
       
        $item = new Item();
        $item->setName($customer->f_name)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($order_amount)
            ->setSku($order_id);

        $item_list = new ItemList();
        $item_list->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency(Helpers::currency_code())
            ->setTotal($order_amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($tr_ref);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paypal-payment-success', ['callback' => $callback, 'transaction_reference' => $tr_ref, 'order_id' => $order_id]))

            ->setCancelUrl(route('paypal-payment-fail', ['callback' => $callback, 'transaction_reference' => $tr_ref]));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->_api_context);

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() === 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            if (isset($redirect_url)) {
                return response()->json(['approval_url' => $redirect_url, 'payment_id' => $payment->getId()]);
            }

        } catch (Exception $ex) {
            Log::error('PayPal Payment Error: ' . $ex->getMessage());
            return response()->json(['error' => 'Your currency is not supported by PayPal.'], 400);
        }

        return Response::json(['error' => 'Configure your PayPal account.'], 400);
    }
}
