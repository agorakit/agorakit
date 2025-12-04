<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutBannedUser
{
    /**
     * Filter and logout banned users
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Guest : simply pass
        if (Auth::guest()) {
            return $next($request);
        }

        // User is banned : logout, invalidate session and redirect to homepage
        if ($request->user()->isBanned()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            flash(trans('messages.you_are_banned'));
            return redirect('/');
        }

        // All other cases : pass
        return $next($request);
    }
}
