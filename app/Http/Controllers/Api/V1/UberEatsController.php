<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UberEatsController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        Log::info('Uber Eats webhook received', [
            'event' => $request->input('event_type') ?? $request->input('event'),
            'order_id' => $request->input('order_id') ?? $request->input('external_order_id'),
        ]);

        return response()->json(['status' => 'received']);
    }
}
