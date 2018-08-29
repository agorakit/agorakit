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
        'App\Events\ContentCreated' => [
            'App\Listeners\NotifyMentionedUsers',
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
    }
}
