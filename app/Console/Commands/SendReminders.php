<?php

namespace App\Console\Commands;

use App\Group;
use App\CalendarEvent;
use App\Notifications\UpcomingCalendarEvent;
use App\User;
use Carbon\Carbon;
use Notification;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
    *  The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:sendreminders {--batch=1000}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Sends all reminders to participants who asked for it - call exactly every 5 minutes, no more, no less :-)';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
      // get all events that starts exactly in one hour and notify users
        $events = CalendarEvent::whereBetween('start', [Carbon::now()->addHour()->subMinutes(5), Carbon::now()->addHour()])->get();

        foreach ($events as $event) {
            $users =  $event->attending()->where('notification', 60)->get();
            Notification::send($users, new UpcomingCalendarEvent($event));
            if ($users->count() > 0) {
                $this->info($users->count() . ' users notified for ' . $event->name);
            }
        }

      // get all events that starts exactly in one day and notify users
        $events = CalendarEvent::whereBetween('start', [Carbon::now()->addDay()->subMinutes(5), Carbon::now()->addDay()])->get();

        foreach ($events as $event) {
            $users =  $event->attending()->where('notification', 60 * 24)->get();
            Notification::send($users, new UpcomingCalendarEvent($event));
            if ($users->count() > 0) {
                $this->info($users->count() . ' users notified for ' . $event->name);
            }
        }
    }
}
