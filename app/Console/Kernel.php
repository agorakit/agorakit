<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
    * The Artisan commands provided by your application.
    *
    * @var array
    */
    protected $commands = [
        \App\Console\Commands\SendNotifications::class,
        \App\Console\Commands\CheckMailbox::class,
        \App\Console\Commands\DeleteFiles::class,
        \App\Console\Commands\CleanupDatabase::class,
        \App\Console\Commands\CleanupDisk::class,
        \App\Console\Commands\SendReminders::class,
        \App\Console\Commands\ExportGroup::class,
        \App\Console\Commands\ImportGroup::class,
    ];

    /**
    * Define the application's command schedule.
    *
    * @param \Illuminate\Console\Scheduling\Schedule $schedule
    *
    * @return void
    */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('agorakit:sendnotifications')
        ->everyMinute();

        $schedule->command('agorakit:checkmailbox')
        ->everyMinute();

        $schedule->command('agorakit:sendreminders')
        ->everyFiveMinutes();

        $schedule->command('agorakit:cleanupdatabase')
        ->daily();

        $schedule->command('agorakit:cleanupdisk')
        ->hourly();
    }
}
