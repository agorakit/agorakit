<?php

namespace App\Http;

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
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\RedirectLang::class, // this one will be fine for now as far as I can tell :-)
            //\App\Http\Middleware\RedirectLangByDomain::class, // Up to you to choose ;-)
        ],

        'api' => [
            'throttle:60,1',
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
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'member' => \App\Http\Middleware\RedirectIfNotGroupMember::class,
        'verified' => \App\Http\Middleware\Verified::class,
        'cache' => \App\Http\Middleware\Cache::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'public' => \App\Http\Middleware\RedirectIfNotGroupMemberOrPublicGroup::class,
        'elfinder' => \App\Http\Middleware\ElfinderMiddleware::class,
        'menu' => \App\Http\Middleware\MenuMiddleware::class
    ];
}
