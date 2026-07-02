<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Model\Order;

class PaypalPaymentController extends Controller
{
    public function __construct()
    {
        //configuration initialization
        $mode = env('APP_MODE');
        $paypal = Helpers::get_business_settings('paypal');
        if ($paypal) {
            if ($mode == 'live') {
                $paypal_mode = "live";
            } else {
                $paypal_mode = "sandbox";
            }

            $config = array(
                'client_id' => $paypal['paypal_client_id'], // values : (local | production)
                'secret' => $paypal['paypal_secret'],
                'settings' => array(
                    'mode' => env('PAYPAL_MODE', $paypal_mode), //live||sandbox
                    'http.ConnectionTimeOut' => 30,
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path() . '/logs/paypal.log',
                    'log.LogLevel' => 'ERROR'
                ),
            );
            Config::set('paypal', $config);
        }

        //
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function payWithpaypal(Request $request)
    {	
		$order = Order::findorFail($request->input('order_id'));
	   	
        $order_amount = $order->order_amount;
        $order_id = $request->input('order_id');
        $customer = User::where('id', $order->user_id)->first();
        $callback = $request['callback'];

        $tr_ref = Str::random(6) . '-' . rand(1, 1000);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items_array = [];
        $item = new Item();
        $item->setName($customer['f_name'])
            ->setCurrency(Helpers::currency_code())
            ->setQuantity(1)
            ->setPrice($order_amount);
        array_push($items_array, $item);

        $item_list = new ItemList();
        $item_list->setItems($items_array);

        $amount = new Amount();
        $amount->setCurrency(Helpers::currency_code())
            ->setTotal($order_amount);

        \session()->put('transaction_reference', $tr_ref);
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
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }

        } catch (\Exception $ex) {
            Toastr::error('Your currency is not supported by PAYPAL.');
            return back()->withErrors(['error' => 'Failed']);
        }

        Session::put('error', 'Configure your paypal account.');
        return back()->withErrors(['error' => 'Failed']);
    }
	
	 public function success(Request $request)
	{		
			// Check if there's a callback URL stored in the session
			if (session()->has('callback')) {
				return redirect(session('callback') . '/success');
			}
			
			// Retrieve the payment data from the request (you can get it from the session or request)
			$paymentData = $request->only(['transaction_reference', 'order_id', 'paymentId', 'token', 'PayerID']);
		
			// Find the order by order_id
			$order = Order::where('transaction_reference', $paymentData['transaction_reference'])->first();

			// If the order is found, update the payment details
			if ($order) {
				$order->transaction_reference = $paymentData['transaction_reference'];
				$order->payment_status = 'paid';
				$order->payment_method = 'digital_payment';
				$order->order_status = 'confirmed';  
				$order->save();  
			}

		
		// Return the success view
		return view('payment-success');
	}

    /**
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
     public function fail()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/fail');
        }

        return view('payment-failed');

    }
	
	

    /**
     * @param Request $request
     * @return Redirector|RedirectResponse|Application
     */
    public function getPaymentStatus(Request $request): Redirector|RedirectResponse|Application
    {
        $callback = $request['callback'];
        $transaction_reference = $request['transaction_reference'];

        $payment_id = Session::get('paypal_payment_id');
        if (empty($request['PayerID']) || empty($request['token'])) {
            Session::put('error', 'Payment failed');
            return Redirect::back();
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        //token string generate
        $transaction_reference = $payment_id;
        $token_string = 'payment_method=paypal&&transaction_reference=' . $transaction_reference;

        if ($result->getState() == 'approved') {
            //success
            if ($callback != null) {
                return redirect($callback . '/success' . '?token=' . base64_encode($token_string));
            } else {
                return \redirect()->route('payment-success', ['token' => base64_encode($token_string)]);
            }
        }

        //fail
        if ($callback != null) {
            return redirect($callback . '/fail' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-fail', ['token' => base64_encode($token_string)]);
        }
    }
}
