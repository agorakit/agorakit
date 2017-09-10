<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    /**
    * The Guard implementation.
    *
    * @var Guard
    */
    protected $auth;

    /**
    * Create a new filter instance.
    *
    * @param  Guard  $auth
    * @return void
    */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
    * Check if the current user is an admin
    */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return redirect()->guest('login')->with('message', trans('messages.not_logged_in'));
        }

        if ($request->user()->isAdmin())
        {
            return $next($request);
        }
        else
        {
            flash()->error(trans('messages.you_are_not_an_admin'));
            return response(trans('messages.you_are_not_an_admin'), 401);
        }
    }
}
