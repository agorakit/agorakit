<?php

namespace App\Providers;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
