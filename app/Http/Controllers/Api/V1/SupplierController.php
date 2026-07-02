<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Supplier API is not configured for this package.',
        ], 501);
    }

    public function update(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Supplier API is not configured for this package.',
        ], 501);
    }
}
