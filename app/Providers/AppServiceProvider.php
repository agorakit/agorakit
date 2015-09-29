<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

      // adds user info to all views TODO : refactor
      view()->composer('*', function($view)
        {
          $view->with('user_logged', Auth::check() );

          if (Auth::check() )
          {
            $view->with('user', compact(Auth::user()->name) );
          }
        });

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
