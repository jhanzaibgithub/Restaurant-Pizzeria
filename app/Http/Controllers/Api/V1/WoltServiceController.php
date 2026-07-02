<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\WoltService;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WoltServiceController extends Controller
{
    private $woltService;
    private $order;

    public function __construct(WoltService $woltService, Order $order)
    {
        $this->woltService = $woltService;
        $this->order = $order;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

	   public function createShipment(Request $request): JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'lat' => 'required',
			'lng' => 'required',
		]);

		if ($validator->fails()) {
			return response()->json([
				'errors' => [
					[
						'message' => 'Validation failed',
						'code' => "1"
					]
				]
			], 403);
		}

		$data = [
			'lat' => $request->input('lat', '0'),
			'lon' => $request->input('lng', '0'),
			'street' => $request->input('address', null),
			'city' => $request->input('address', null),
			'min_preparation_time_minutes' => 5,
			'language' => 'en'
		];

		$result = $this->woltService->getShipmentPromises($data);

		if (isset($result['id'])) {
			$driver = [
				'wolt_promise_id' => $result['id'],
				'delivery_price' => $result['price']['amount']
			];

			return response()->json(['data' => $driver], 200);
		}

		// Construct the error response in the specified format
		$errors = [
			[
				'message' => $result['error'] ?? 'Dropoff location is outside of the delivery area',
				'code' => "1"
			]
		];

		return response()->json(['errors' => $errors], 404);
	}
    public function createDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = $this->order->find($request['order_id']);


            try{
                $result = $this->woltService->createDelivery($order);

                if(isset($result['url'])){
                    $order->wolt_tracking_url = $result['url'];
                    $order->wolt_driver = 1;
                    $order->save();
                }

                return response()->json($result);

            } catch (\Exception $e) {
                 return response()->json([
                'errors' => [
                    ['message' => $e->getMessage()]
                ]
            ], 404);
            }

        return response()->json([
                'errors' => [
                    ['message' => translate('Driver not Found!')]
                ]
            ], 404);
    }

    public function handleWoltWebhook(Request $request)
    {
        $token = $request->input('token');

        try {
            // Split the JWT token into its three parts
            list($header, $payload, $signature) = explode('.', $token);
            $decodedPayload = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            // Extract event type and details
            $eventType = $decodedPayload['type'] ?? null;
            $details = $decodedPayload['details'] ?? null;

            // Extract the merchant_order_reference_id which is your order ID
            $orderId = $details['merchant_order_reference_id'] ?? null;

            // Find the order by the merchant_order_reference_id
            $order = $this->order->find($orderId);

            // Handle the event based on the event type
            switch ($eventType) {
                case 'order.pickup_started':
                    $order->order_status = 'out_for_delivery'; // Update the status to 'picked_up'
                    $order->save();
                    break;

                case 'order.delivered':
                    $order->order_status = 'delivered'; // Update the status to 'delivered'
                    $order->save();
                    break;

                default:
                    // info("Unhandled event type: {$eventType} for order {$orderId}");
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            // Handle the exception if something goes wrong
            // info('Failed to decode JWT token or process event: ' . $e->getMessage());
            return false;
        }
    }
}
