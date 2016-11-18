<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class ElfinderMiddleware
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
        // we expect a url in the form /groups/{group_id}
        if ($request->segment(1) == 'groups')
        {
            $group = \App\Group::findOrFail($request->segment(2));
            Config::set('elfinder.dir', 'groups/' . $group->id);
            return $next($request);

        }
        return $next($request);

    }
}
