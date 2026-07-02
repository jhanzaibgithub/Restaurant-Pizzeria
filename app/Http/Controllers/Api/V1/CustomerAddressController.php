<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CustomerAddress; 
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    public function store_address(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Ensure user exists in users table
            'address' => 'required|string|max:255',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
            'address_type' => 'required|string|max:255',
            'contact_person_number' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the customer address
        $address = CustomerAddress::create([
            'user_id' => $request->user_id,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'address_type' => $request->address_type,
            'contact_person_number' => $request->contact_person_number,
            'contact_person_name' => $request->contact_person_name,

        ]);

        return response()->json(['message' => 'Address created successfully', 'data' => $address], 200);
    }
    
}
