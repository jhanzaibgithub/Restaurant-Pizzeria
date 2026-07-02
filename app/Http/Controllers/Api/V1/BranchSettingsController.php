<?php

namespace App\Http\Controllers\Api\V1;

use App\Country;
use App\Http\Controllers\Controller;
use App\Model\BranchSetting;
use App\Model\Currency;
use Illuminate\Http\Request;

class BranchSettingsController extends Controller
{
    public function get_business_settings(Request $request)
    {
        $branch = $request->branch->id;
        $setting = BranchSetting::where('branch_id', $branch)->first();
        
        if (!$setting) {
            return response()->json([
                'message' => 'Branch settings not found',
            ], 404);
        }
    
        // Load taxes
        $setting->load('taxes');
    
        // Prepare response with full URLs
        $response = $setting->toArray();
        $response['fav_icon'] = $setting->fav_icon ? asset('storage/assets/branch/' . basename($setting->fav_icon)) : null;
        $response['banner_image'] = $setting->banner_image ? asset('storage/assets/branch/' . basename($setting->banner_image)) : null;
    
        // Convert integer status to boolean for taxes
        $response['taxes'] = $setting->taxes->map(function ($tax) {
            return [
                'id' => $tax->id,
                'tax_type' => $tax->tax_type,
                'tax_rate' => $tax->tax_rate,
                'status' => (bool) $tax->status, // Convert to boolean
                'created_at' => $tax->created_at,
                'updated_at' => $tax->updated_at,
            ];
        })->toArray();
    
        return response()->json([
            'message' => 'Branch settings retrieved successfully',
            'data' => $response,
        ]);
    }
    
    
    public function update_setting(Request $request)
    {
        info($request->all());
        $branch = $request->branch->id; 
        // Validate the request
        $data = $request->validate([
            'restaurant_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'country' => 'string',
            'time_zone' => 'string',
            'time_format' => 'string',
            'currency' => 'string',
            'currency_position' => 'string',
            'digit_after_decimal' => 'integer',
            'copyright_text' => 'nullable|string',
            'pagination' => 'integer',
            'min_order_value' => 'numeric',
            'food_preparation_time' => 'integer',
            'schedule_order_slot_duration' => 'integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'coverage_km' => 'nullable|integer',
            'self_pickup' => 'boolean',
            'delivery' => 'boolean',
            'email_verification' => 'boolean',
            'phone_verification' => 'boolean',
            'deliveryman_self_registration' => 'boolean',
            'veg_non_veg_option' => 'boolean',
            'status' => 'boolean',
            'fav_icon' => 'nullable|file|mimes:jpg,jpeg,png',
            'banner_image' => 'nullable|file|mimes:jpg,jpeg,png',
            'tax_details' => 'nullable|array', // Validate tax_details as an array
            'tax_details.*.tax_type' => 'required_with:tax_details|string',
            'tax_details.*.tax_rate' => 'required_with:tax_details|numeric|min:0|max:100',
            'tax_details.*.status' => 'required_with:tax_details|boolean',
        ]);
    
        // Find or create the branch settings
        $setting = BranchSetting::where('branch_id', $branch)->first();
        if (!$setting) {
            $setting = new BranchSetting();
            $setting->branch_id = $branch;
        }
    
        // Handle file uploads for fav_icon
        if ($request->hasFile('fav_icon')) {
            // Delete old fav_icon if exists
            if ($setting->fav_icon && Storage::exists($setting->fav_icon)) {
                Storage::delete($setting->fav_icon);
            }
    
            $file = $request->file('fav_icon');
            $customName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $data['fav_icon'] = $file->storeAs('branch', $customName); // Save to 'storage/branch'
        }
    
        // Handle file uploads for banner_image
        if ($request->hasFile('banner_image')) {
            // Delete old banner_image if exists
            if ($setting->banner_image && Storage::exists($setting->banner_image)) {
                Storage::delete($setting->banner_image);
            }
    
            $file = $request->file('banner_image');
            $customName = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $data['banner_image'] = $file->storeAs('branch', $customName); // Save to 'storage/branch'
        }
    
        // Remove tax_details from $data to avoid SQL errors
        unset($data['tax_details']);
    
        // Update branch settings
        $setting->fill($data);
        $setting->save();
    
        // Handle tax details in the separate branch_taxes table
        if ($request->has('tax_details') && is_array($request->input('tax_details'))) {
            $this->update_taxes($setting->id, $request->input('tax_details'));
        }
    
        // Reload taxes and include them in the response
        $setting->load('taxes');
    
        // Prepare response with full URLs
        $response = $setting->toArray();
        $response['fav_icon'] = $setting->fav_icon ? asset('storage/assets/branch/' . basename($setting->fav_icon)) : null;
        $response['banner_image'] = $setting->banner_image ? asset('storage/assets/branch/' . basename($setting->banner_image)) : null;
    
        return response()->json([
            'message' => 'Settings and taxes updated successfully',
            'data' => $response,
        ], 200);
    }
    
    private function update_taxes($branchSettingId, $taxDetails)
    {
        $branchSetting = BranchSetting::findOrFail($branchSettingId);
    
        // Delete old taxes
        $branchSetting->taxes()->delete();
    
        // Add new taxes
        foreach ($taxDetails as $tax) {
            $branchSetting->taxes()->create([
                'tax_type' => $tax['tax_type'],
                'tax_rate' => $tax['tax_rate'],
                'status' => $tax['status'],
            ]);
        }
    }
    
    
    public function settings_Dropdown(){

        $currencies = Currency::pluck('currency_symbol')->toArray();

        $timezones = Country::pluck('time_zone')->toArray();

        $countries = Country::pluck('name')->toArray();
    
        return response()->json([
            'currencies' => $currencies,
            'timezones' => $timezones,
            'countries' => $countries,
        ]);
    }
    
    
}
