<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Auth;

use App\Helpers\QueryHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

      // adds user info to all views TODO : refactor
      view()->composer('partials.nav', function ($view) {
          $view->with('user_logged', Auth::check());

          if (Auth::check()) {
              $view->with('user', Auth::user());

              // count number of unread discussions.
              $view->with('unread_discussions', QueryHelper::getUnreadDiscussionsCount() );

              $view->with('user_groups', QueryHelper::getUserGroups() );



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
