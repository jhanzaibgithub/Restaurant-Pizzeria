<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    /**
     * @throws ApiErrorException
     */
    public function create_intent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $currency_code = strtoupper(Helpers::get_business_settings('currency') ?? 'usd');
        $amount = $this->stripeAmount((float)$request['order_amount'], $currency_code);
        $config = Helpers::get_business_settings('stripe');

        Stripe::setApiKey($config['api_key']);

        $payment_intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => strtolower($currency_code),
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'metadata' => [
                'customer_id' => (string)$request->user()->id,
            ],
        ]);

        return response()->json([
            'payment_intent_id' => $payment_intent->id,
            'client_secret' => $payment_intent->client_secret,
        ], 200);
    }

    private function stripeAmount(float $amount, string $currency_code): int
    {
        $currencies_not_supported_cents = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        return in_array($currency_code, $currencies_not_supported_cents)
            ? (int)round($amount)
            : (int)round($amount * 100);
    }
}
