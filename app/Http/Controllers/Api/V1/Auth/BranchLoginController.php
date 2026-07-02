<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BranchLoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request['email'],
            'password' => $request->password,
        ];

        if (auth('branch')->attempt($data)) {
            $token = $this->genarate_token($request['email']);
            $branch = Branch::where(['email' => $request['email']])->first();
            $branch->auth_token = $token;
            $branch->fcm_token = $request['fcm_token']??$branch->fcm_token;
            $branch->save();
            return response()->json([
                'user' => $branch,
                'token' => $token,
                'message' => translate('Successfully login.')
            ], 200);
        }

        $errors = [];
        $errors[] = ['code' => 'auth-001', 'message' => translate('Invalid credential.')];
        return response()->json(['errors' => $errors], 401);
    }

    private function genarate_token($email)
    {
        $token = Str::random(70);
        $is_available = Branch::where('auth_token', $token)->where('email', '!=', $email)->count();
        if($is_available)
        {
            $this->genarate_token($email);
        }
        return $token;
    }

}
