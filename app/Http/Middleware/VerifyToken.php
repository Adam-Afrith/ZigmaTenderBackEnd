<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Token;

class VerifyToken
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
        $tokenid = $request->tokenid;
        $token = Token::where('tokenid',$tokenid)->where('isLoggedIn',1)->first();
        if(!$token){
            return response()->json([
                'error' => 'Unauthorized'
            ],401);

        }
        return $next($request);
    }
}
