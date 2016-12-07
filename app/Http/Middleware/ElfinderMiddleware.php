<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class ElfinderMiddleware
{
    /**
    * This middleware sets the correct elfinder "root" for each group. It also creates the group folder if it doesn't exists already.
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
            $group_id = $request->segment(2);

            if(!\File::exists(public_path() . '/files/groups/' . $group_id))
            {
                $group = \App\Group::findOrFail($request->segment(2));
                \File::makeDirectory(public_path() . '/files/groups/' . $group->id);
            }

            Config::set('elfinder.dir', '/files/groups/' . $group_id);

            return $next($request);

        }
        return $next($request);

    }
}
