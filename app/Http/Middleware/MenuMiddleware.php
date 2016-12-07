<?php

namespace App\Http\Middleware;

use Closure;
use Menu;

class MenuMiddleware
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        Menu::make('navbar', function($menu){

            $menu->add('Home', ['action' => 'DashboardController@index']);
            $menu->add('Groups',    'groups');
            $menu->add('Overview', 'discussions');
            $menu->add('Contact',  'contact');

        });
        return $next($request);
    }
}
