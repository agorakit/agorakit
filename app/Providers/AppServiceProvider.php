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
            //dd (Auth::user());
            $view->with('user', compact(Auth::user()->name) );
            $view->with('user_is_admin', Auth::user()->isAdmin() );

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
