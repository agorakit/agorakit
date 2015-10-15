<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfNotGroupMember
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if current user is not a member of the passed in group
        // if (! $request->user()->groups

        if ($this->auth->guest())
        {
            return redirect()->back()->with('message', 'You need to be logged so I can check if you are a member of this group');
        }

        if ($request->segment(1) == 'groups')
        {
            $group = \App\Group::findOrFail($request->segment(2));


            if ($group->users()->where('user_id', $request->user()->id)->count() == 1)
            {
                return $next($request);
            }
            else
            {
                return redirect()->back()->with('message', 'You are not a member of this group');
            }

        }
        else
        {
                return redirect()->back()->with('message', 'Are you in a group?');
        }





    }
}
