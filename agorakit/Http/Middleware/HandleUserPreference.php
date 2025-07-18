<?php

namespace Agorakit\Http\Middleware;

use Auth;
use Closure;

class HandleUserPreference
{
    /**
     * Handle user preferences. Stores the preference for the current logged in user
     * yoururl?set_preference=preference_name&value=thevalue
     * will store the preference.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return $next($request);
        }

        if ($request->has('set_preference')) {
            $request->user()->setPreference($request->set_preference, $request->value);
        }

        return $next($request);
    }
}
