<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Facades\Context;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // set correct locale for Carbon
        Carbon::setLocale(config('app.locale'));
        Schema::defaultStringLength(191);

        // Set HTTPS everywhere in production
        if (config('app.env') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
        // add the context facade to all views
        View::share('Context', Context::getFacadeRoot());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
