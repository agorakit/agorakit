<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfNotGroupMemberOrPublicGroup
{
    /**
    * The Guard implementation.
    *
    * @var Guard
    */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure                 $next
    *
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        // we expect a url in the form /groups/{group_id}
        if ($request->segment(1) == 'groups')
        {
            if ($this->auth->guest())
            {
                $group = \App\Group::findOrFail($request->segment(2));
                if ($group->isPublic())
                {
                    return $next($request);
                }
                else
                {
                    return redirect()->back()->with('message', trans('messages.not_allowed'));
                }
            }
            else // user is logged
            {
                $group = \App\Group::findOrFail($request->segment(2));
                if ($group->isPublic()) // group is public, fine
                {
                    return $next($request);
                }
                elseif ($group->isMember()) // user is memberof the group, fine
                {
                    return $next($request);
                }
                else
                {
                    return redirect()->back()->with('message', trans('messages.not_allowed'));
                }
            }
        }
        else
        {
            return redirect()->back()->with('message', 'Are you in a group at all !? (url doesnt start with group/something). This is a bug');
        }
    }
}
