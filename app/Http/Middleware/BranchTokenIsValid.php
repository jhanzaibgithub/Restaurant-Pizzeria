<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Branch;

class BranchTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token=$request->bearerToken();
        if(strlen($token)<1)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'auth-001', 'message' => 'Unauthorized.']
                ]
            ], 401);
        }
        $branch = Branch::where('auth_token',$token)->first();
        if($branch)
        {
            $request['branch']=$branch;
            return $next($request);
        }
        return response()->json([
            'errors' => [
                ['code' => 'auth-001', 'message' => 'Unauthorized.']
            ]
        ], 401);
    }
}
