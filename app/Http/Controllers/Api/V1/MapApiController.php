<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class MapApiController extends Controller
{
    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function place_api_autocomplete(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $api_key);

        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function distance_api(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $api_key);

        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function place_api_details(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $api_key);

        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function geocode_api(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $api_key = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $api_key);

        return $response->json();
    }
    
     /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function get_nearest_branch(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
    
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
    
        $api_key = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->latitude . ',' . $request->longitude . '&key=' . $api_key);
    
        $data = $response->json();
        
        // Check if the response contains results
        if (isset($data['results']) && !empty($data['results'])) {
           
            $result = $data['results'][0];
           
            $city = '';
            $locationFinalName = $result['formatted_address'];

            foreach ($result['address_components'] as $component) {
                if (in_array('locality', $component['types'])) {
                    $city = $component['long_name'];
                }
            }

            if (empty($locationFinalName)) {
                $locationFinalName = 'No specific point of interest found';
            }

             $branches = Branch::selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$request->latitude, $request->longitude, $request->latitude])
            ->whereRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) <= coverage', [$request->latitude, $request->longitude, $request->latitude])
            ->orderBy('distance')
            ->first();
            
            $branch_id = $branches ? $branches->id : "";
            
            if($branch_id){
                $responseArray = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $locationFinalName,
                'city' => $city,
                'branch_id' => $branch_id,
                ];
                return response()->json($responseArray);
            }else{
                return response()->json([
                    'errors' => [
                        ['code' => 'service', 'message' => translate('Service not available in this area')]
                    ]
                ], 404);
            }
            
        }
    
        return response()->json(['message' => 'No results found'], 404);
    }
}
