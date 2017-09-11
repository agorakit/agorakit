<?php

namespace App\Providers;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // adds user info to all views
        /*
        view()->composer('partials.nav', function ($view) {
            $view->with('user_logged', Auth::check());

            if (Auth::check()) {
                $view->with('user', Auth::user());

            }
        });
        */

        // set correct locale for Carbon
        Carbon::setLocale(config('app.locale'));
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
