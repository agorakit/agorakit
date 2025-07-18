<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Verified
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
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Check if the current user is verified ((s)he verified the email adress).
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return redirect()->guest('login')->with('message', trans('messages.not_logged_in'));
        }

        if ($request->user()->isVerified()) {
            return $next($request);
        } else {
            return redirect('/')->with('message', trans('messages.email_not_verified'));
        }
    }
}
