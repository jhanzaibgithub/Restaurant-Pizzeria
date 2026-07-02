<?php

namespace App\Http\Controllers;

use App\Model\Order;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Unicodeveloper\Paystack\Facades\Paystack;
use Validator;
use App\User;

class PaystackController extends Controller
{
    public function __construct()
    {

        $paystack = Helpers::get_business_settings('paystack');
        if ($paystack) {
            $config = array(
                'publicKey' => env('PAYSTACK_PUBLIC_KEY', $paystack['publicKey']),
                'secretKey' => env('PAYSTACK_SECRET_KEY', $paystack['secretKey']),
                'paymentUrl' => env('PAYSTACK_PAYMENT_URL', $paystack['paymentUrl']),
                'merchantEmail' => env('MERCHANT_EMAIL', $paystack['merchantEmail']),
            );

             Config::set('paystack', $config);
        }
    }

public function redirectToGateway(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'order_id' => 'required|exists:orders,id',
    ]);

    // Handle validation errors
    if ($validator->fails()) {
        return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    }
	
    // Fetch customer and order details
    $currency = Helpers::currency_code() ?? 'ZAR';
    $order = Order::find($request->input('order_id'));
	$customer = User::find($order->user_id);
    $reference = self::generate_transaction_Reference();
	
	$order->update([
   	 'transaction_reference' => $reference,
	]);
 	 \session()->put('transaction_reference', $reference);
	 \session()->put('order_id', $order->id);

    $paystackPayload = [
        'email' => $customer->email,
        'orderID' => $order->id,
        'quantity' => 1,
        'amount' => $order->order_amount * 100, // Convert to smallest currency unit (e.g., kobo)
        'currency' => $currency,
        'reference' => $reference,
		'callback_url' => route('paystack-callback'),
    ];

    // Redirect to Paystack authorization URL
    try {
        return Paystack::getAuthorizationUrl($paystackPayload)->redirectNow();
    } catch (\Exception $exception) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to create Paystack authorization URL.',
            'error' => $exception->getMessage(),
        ], 500);
    }
}

    public function handleGatewayCallback()
    {	
        $paymentDetails = Paystack::getPaymentData();
		
        $order = Order::where(['transaction_reference' => $paymentDetails['data']['reference']])->first();
		
        if ($paymentDetails['status'] == true) {
            $order->payment_status = 'paid';
            $order->order_status = 'confirmed';
            $order->save();
            try {
                //Helpers::send_order_notification($order);
            } catch (\Exception $e) {}
            if ($order->callback != null) {
                return redirect($order->callback . '&status=success');
            }else{
                return \redirect()->route('paystack-pay-success');
            }
        } else {
            DB::table('orders')
            ->where('id', $order['id'])
            ->update([
                'payment_method' => 'paystack',
                'order_status' => 'failed',
                'failed' => now(),
                'updated_at' => now(),
            ]);
            if ($order->callback != null) {
                return redirect($order->callback . '&status=fail');
            }else{
                return \redirect()->route('paystack-pay-fail');
            }
        }
    }

    /**
     * @return mixed
     */
    public static function generate_transaction_Reference(): mixed
    {
        return Paystack::genTranxRef();
    }
	
	 public function success(Request $request)
	{		
			// Check if there's a callback URL stored in the session
			if (session()->has('callback')) {
				return redirect(session('callback') . '/success');
			}
		 
			$order_id = session()->get('order_id');
			
			// Retrieve the payment data from the request (you can get it from the session or request)
			$paymentData = $request->only(['transaction_reference', 'order_id']);
			
			// Find the order by order_id
			$order = Order::where('id', $order_id)->first();

			// If the order is found, update the payment details
			if ($order) {
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
	
	
}
