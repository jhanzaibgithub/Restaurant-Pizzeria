<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Model\BusinessSetting;
use App\Http\Controllers\Controller;

class BussinessSettingController extends Controller
{

    public function updateTax(Request $request)
    {
       
        $request->validate([
            'type' => 'required|string',
            'tax_type' => 'required|string',
            'percentage' => 'required|numeric', 
        ]);

        // Retrieve the key (type) from the request
        $type = $request->type;
        $taxType = $request->tax_type;
        $percentage = $request->percentage;

        // Find the business setting for the specified key
        $businessSetting = BusinessSetting::where('key', $type)->first();

        if ($businessSetting) {
            // Decode the JSON value
            $value = json_decode($businessSetting->value, true);

            // Update the JSON fields
            $value['tax_type'] = $taxType;
            $value['percentage'] = $percentage;

            // Encode the updated JSON and save back to the database
            $businessSetting->value = json_encode($value);
            $businessSetting->save();

            return response()->json(['message' => 'Tax settings updated successfully!']);
        }

        // Return error if the key is not found
        return response()->json(['message' => 'Business setting not found.'], 404);
    }

}
