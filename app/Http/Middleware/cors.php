<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class cors
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
        return $next($request)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With')
      ->header('Access-Control-Allow-Credentials',' true');
      
    //   $headers = [
    //     'Access-Control-Allow-Origin' => '*',
    //     'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, OPTIONS',
    //     'Access-Control-Allow-Headers' => 'Authorization, Content-Type',
    //     'Access-Control-Allow-Credentials' => 'true',
    // ];

    //   if ($request->getMethod() == 'OPTIONS') {
    //     return response('', 200)->withHeaders($headers);
    // }

    // $response = $next($request);
    // foreach ($headers as $key => $value) {
    //     $response->header($key, $value);
    // }

    // return $response;

    }
}
