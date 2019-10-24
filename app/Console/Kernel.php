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
        \App\Console\Commands\ConvertFiles::class,
        \App\Console\Commands\ConvertFoldersToTags::class,
        \App\Console\Commands\ImportActions::class,
        \App\Console\Commands\PopulateFilesize::class,
        \App\Console\Commands\CheckMailbox::class,
        \App\Console\Commands\DeleteFiles::class,
        \App\Console\Commands\CleanupDatabase::class,
        \App\Console\Commands\SendReminders::class,
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
        ->everyFiveMinutes()
        ->appendOutputTo(storage_path().'/logs/notifications.log');

        $schedule->command('agorakit:checkmailbox')
        ->everyFiveMinutes()
        ->appendOutputTo(storage_path().'/logs/mailbox.log');

        $schedule->command('agorakit:sendreminders')
        ->everyFiveMinutes()
        ->appendOutputTo(storage_path().'/logs/mailbox.log');

        $schedule->command('agorakit:cleanupdatabase')
        ->daily()
        ->appendOutputTo(storage_path().'/logs/cleanup.log');
    }
}
