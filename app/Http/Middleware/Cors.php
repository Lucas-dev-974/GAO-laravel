<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        /* @var $response Response */
        $response = $next($request);
        if (!$request->isMethod('OPTIONS')) {
            return $response;
        }
        $allow = $response->headers->get('Allow'); // true list of allowed methods
        if (!$allow) {
            return $response;
        }
        $headers = [
            'Access-Control-Allow-Origin', '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Max-Age' => 3600,
            'Access-Control-Allow-Headers' => 'X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept',
        ];
        return $response->withHeaders($headers);
    }
}
