<?php

namespace Agorakit\Console;

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
        \Agorakit\Console\Commands\SendNotifications::class,
        \Agorakit\Console\Commands\CheckMailbox::class,
        \Agorakit\Console\Commands\DeleteFiles::class,
        \Agorakit\Console\Commands\CleanupDatabase::class,
        \Agorakit\Console\Commands\CleanupDisk::class,
        \Agorakit\Console\Commands\SendReminders::class,
        \Agorakit\Console\Commands\ExportGroup::class,
        \Agorakit\Console\Commands\ImportGroup::class,
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
