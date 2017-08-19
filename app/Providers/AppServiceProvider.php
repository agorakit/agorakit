<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use App\Helpers\QueryHelper;
use Carbon\Carbon;
use Session;

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
