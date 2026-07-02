<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Model\Order;

class PayconiqController extends Controller
{
    private $apiKey;
    private $url;
    private $host;

    public function __construct()
    {
        $mode = env('PAYCONIQ_MODE');
        $payconiq = Helpers::get_business_settings('payconiq_payment');
        $this->url = "https://api.payconiq.com/v3/payments"; 
        $this->host = '';
        $this->apiKey = null;
	
        if ($payconiq) {
			$this->apiKey = $payconiq['token'];
            if ($mode == 'test') {
                $this->url = "https://api.ext.payconiq.com/v3/payments";
                $this->host = 'ext.';
				$this->apiKey = "4a24422b-e2f1-4c77-a81f-59fc9f13602a";
            }
        }
    }

    public function createTransaction(Request $request)
    {		
		
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);
        $amount = (float) $order->order_amount;
        $amountInCents = intval($amount * 100); // Convert the amount to cents

        $payload = [
            'amount' => $amountInCents,
            'currency' => 'EUR',
            'callbackUrl' => route('payconiq_callback'),
            'reference' => (string) $orderId,
            'returnUrl' => route('payconiq_success'),
        ];

        // Make the API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->url, $payload);
			
        if ($response->successful()) {
            $responseData = $response->json();
            session(['payment_id' => $responseData['paymentId']]);
            $deeplinkUrl = $responseData['_links']['checkout']['href'];
            $modifiedDeeplinkUrl = str_replace('https://', "https://{$this->host}", $deeplinkUrl);
		
            return redirect()->away($modifiedDeeplinkUrl);
        }

        return response()->json([
            'message' => 'Failed to create payment.',
            'error' => $response->json()
        ], $response->status());
    }

	public function handlePayconiqCallback(Request $request)
    {
		try{
			// Log the incoming request for debugging purposes
			Log::info('Payconiq callback received.', ['request' => $request->all()]);

			// Extract necessary data from the request
			$paymentId = $request->input('paymentId');
			$status = $request->input('status');  // e.g., PENDING, SUCCESSED, FAILED
			$orderId = $request->input('reference');
			$order = Order::where(['id' => $orderId, 'order_status' => 'failed','payment_method' => 'bancontact'])->first();

			if ($paymentId && $order) {
				
				if($status == 'SUCCEEDED'){
					$order->order_status = 'confirmed';
					$order->payment_status = 'paid';
					$order->transaction_reference = $paymentId;
					$order->save();

					$value = Helpers::order_status_update_message($request->order_status);
					$fcm_token = null;
					if (isset($order->customer)) {
						$fcm_token = $order->customer->cm_firebase_token;
					}

					if ($value) {
						$data = [
							'title' => translate('Order'),
							'description' => $value,
							'order_id' => $order->id,
							'image' => '',
							'type' => 'order_status',
						];
						if (isset($fcm_token)) {
							Helpers::send_push_notif_to_device($fcm_token, $data);
						}
					}
					// delivery man notification
					if($order->order_type != 'take_away'){
						$data = [
							'title' => translate('Order'),
							'description' => translate('new_order_push_description'),
							'order_id' => $order->id,
							'image' => '',
						];
						 Helpers::send_push_notif_to_topic($data, "delivery-man-{$order->branch_id}", 'general');
					}
					
				}else{
					$order->details()->delete();
					$order->delete();
				}

				// Send a response to Payconiq to confirm the callback was received
				return response()->json(['message' => 'Callback received and order updated'], 200);
			} else {
				// Send an error response to Payconiq
				return response()->json(['message' => 'Order not found'], 404);
			}
		} catch (\Exception $e) {
			// Log the exception for debugging
			Log::error('Error handling Payconiq callback: ' . $e->getMessage(), ['exception' => $e]);

			// Return an internal server error response
			return response()->json(['message' => 'Internal server error'], 500);
		}
    }

    public function success(Request $request)
    {
        $paymentId = session('payment_id') ?? '';

        // Payconiq API URL to check the payment status
        $statusUrl = "{$this->url}/{$paymentId}";

        // Make the request to check the payment status
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->get($statusUrl);

        session()->forget('payment_id');
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['status'] === 'SUCCEEDED') {
                return view('payment-success');
            }
        }
        if(empty($paymentId)){
			return view('payment-success');
        }

        return view('payment-failed');
    }
}

