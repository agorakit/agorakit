<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Auth;

class EventServiceProvider extends ServiceProvider
{
    /**
    * The event listener mappings for the application.
    *
    * @var array
    */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
    * Register any events for your application.
    *
    * @return void
    */
    public function boot()
    {
        parent::boot();

        // TODO : this should be somewhere else
        \App\Discussion::created(function ($discussion) {
            $activity = new \App\Activity;
            $activity->action = 'created';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($discussion);
            $activity->group()->associate($discussion->group);
            $activity->saveOrFail();
        });

    }
}
