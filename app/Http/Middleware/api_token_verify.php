<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class api_token_verify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->headers->has('api_token')){
            
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'api_token header not set'
            ]);
        }
        
        if($request->headers->has('api_token')){
            
            $user = User::where('api_token', $request->header('api_token'))->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'data' => [],
                    'message' => 'Invalid api_token supply'
                ]);
            }else{
                return $next($request);
            }
        }

       
    }
}
