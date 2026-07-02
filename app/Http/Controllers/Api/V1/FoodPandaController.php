<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FoodPandaController extends Controller
{
    public function handleWebhook(Request $request): JsonResponse
    {
        Log::info('Foodpanda webhook received', [
            'event' => $request->input('event_type') ?? $request->input('event'),
            'order_id' => $request->input('order_id') ?? $request->input('external_order_id'),
        ]);

        return response()->json(['status' => 'received']);
    }
}
