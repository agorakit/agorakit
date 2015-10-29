<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

      // adds user info to all views TODO : refactor
      view()->composer('*', function ($view) {
          $view->with('user_logged', Auth::check());

          if (Auth::check()) {
              $view->with('user', Auth::user());

              // count number of unread discussions.



          }




        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
