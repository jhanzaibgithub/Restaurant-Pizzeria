<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Model\BusinessSetting;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Model\Order;

class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return View|Factory|JsonResponse|Application
     */
    public function payment(Request $request): View|Factory|JsonResponse|Application
    {
        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

        $params = explode('&&', base64_decode($request['token']));
        foreach ($params as $param) {
            $data = explode('=', $param);
            if ($data[0] == 'customer_id') {
                session()->put('customer_id', $data[1]);
            } elseif ($data[0] == 'callback') {
                session()->put('callback', $data[1]);
            } elseif ($data[0] == 'order_amount') {
                session()->put('order_amount', $data[1]);
            } elseif ($data[0] == 'product_ids') {
                session()->put('product_ids', $data[1]);
            }
        }

        $customer = User::firstWhere(['id' => session('customer_id'), 'is_active' => 1]);
        $order_amount = session('order_amount');

        if (isset($customer) && isset($order_amount)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);
            $businessSettings = BusinessSetting::whereIn('key', [
                'currency',
                'restaurant_name',
                'logo',
                'point_per_currency',
            ])->pluck('value', 'key');

            $paymentConfigs = [];
            foreach ([
                'ssl_commerz_payment',
                'razor_pay',
                'paypal',
                'stripe',
                'paystack',
                'senang_pay',
                'bkash',
                'paymob',
                'mercadopago',
                'flutterwave',
                'internal_point',
            ] as $paymentSettingKey) {
                $paymentConfigs[$paymentSettingKey] = Helpers::get_business_settings($paymentSettingKey);
            }

            return view('payment-view', [
                'payment_method' => $request['payment_method'],
                'currency' => $businessSettings['currency'] ?? 'USD',
                'currencyCode' => Helpers::currency_code(),
                'restaurantName' => $businessSettings['restaurant_name'] ?? '',
                'restaurantLogo' => $businessSettings['logo'] ?? '',
                'pointPerCurrency' => $businessSettings['point_per_currency'] ?? 0,
                'customer' => $customer,
                'order_amount' => $order_amount,
                'callback' => session('callback'),
                'formattedOrderAmount' => Helpers::set_symbol($order_amount),
                'paymentConfigs' => $paymentConfigs,
            ]);

        }

        if (!isset($customer))
            return response()->json(['errors' => ['message' => 'Customer not found or Unauthenticated']], 403);
        elseif (!isset($order_amount))
            return response()->json(['errors' => ['message' => 'Amount not found']], 403);
        else
            return response()->json(['errors' => ['message' => '']], 403);

    }

     /**
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function success(): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (session()->has('callback')) {

            return redirect(session('callback') . '/success');
        }
       // return view('payment-success');
         return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);

        return view('payment-failed');
         return response()->json(['error' => 'Payment Failed'], 200);

    }
}

