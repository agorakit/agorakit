<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;


/**
 * Warns a user that he has invites to accept or discard
 */
class RedirectIfHasInvites
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
    * Check if the current user has pending invites.
    */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return $next($request);
        }
        foreach ($request->user()->memberships as $membership) {
            if ($membership->membership == \App\Membership::INVITED) {
                $request->user()->has_invites = true;
                return $next($request);
            }
        }

        return $next($request);
    }
}
