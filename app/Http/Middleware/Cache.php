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

         if (Auth::guest())
         {

           // early optimization is the root of all evil. turning off this for now
         //$response->header('Cache-Control', 'no-transform,public,max-age=300,s-maxage=900');

         }
         return $response;
     }
}
