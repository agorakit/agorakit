<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Cache
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Cache-Control', 'no-transform,public,max-age=300,s-maxage=900');
        return $response;
    }
}
