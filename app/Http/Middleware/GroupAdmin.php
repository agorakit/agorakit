<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class GroupAdmin
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

        if ($this->auth->guest()) {
            return redirect()->guest('login')->with('message', trans('messages.not_logged_in'));
        }

        // an admin can bypass membership requirement
        if ($request->user()->isAdmin())
        {
            return $next($request);
        }

        // we expect a url in the form /groups/{group_id}
        if ($request->segment(1) == 'groups') {
            $membership = \App\Membership::where('user_id', '=',  $request->user()->id)->where('group_id', $request->segment(2))->first();

            if ($membership && $membership->membership >= \App\Membership::ADMIN) {
                return $next($request);
            } else {
                return redirect()->back()->with('message', trans('messages.not_a_group_admin'));
            }
        } else {
            return redirect()->back()->with('message', 'Are you in a group at all !? (url doesnt start with group/something). This is a bug');
        }
    }
}
