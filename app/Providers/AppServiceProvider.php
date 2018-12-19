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

    // set correct locale for Carbon
    Carbon::setLocale(config('app.locale'));
    Schema::defaultStringLength(191);
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
