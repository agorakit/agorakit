<?php

namespace Agorakit\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Agorakit\Http\Middleware\TrustProxies::class,
            \Agorakit\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Agorakit\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Agorakit\Http\Middleware\SetLocale::class,
            \Agorakit\Http\Middleware\HandleUserPreference::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'            => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'      => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'        => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'             => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'           => \Agorakit\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'        => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'        => \Agorakit\Http\Middleware\Verified::class,
        'cache'           => \Agorakit\Http\Middleware\Cache::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'admin'           => \Agorakit\Http\Middleware\Admin::class,
        'preferences'     => \Agorakit\Http\Middleware\HandleUserPreference::class,
    ];
}
