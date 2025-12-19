<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Closure;
use Log;

/**
 * Adds the user_id, username and path to logs in order to ease debugging bugs in production
 */
class AddContextToLogs
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            Log::shareContext(array_filter([
                'user_id' => $request->user()->id,
                'username' => $request->user()->name,
                // Add more context here if needed
            ]));
        }
        Log::shareContext(['path' => Str::limit($request->path(), 255)]);
        return $next($request);
    }
}