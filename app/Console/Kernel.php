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
        \App\Console\Commands\ProcessMessages::class,
        \App\Console\Commands\DeleteFiles::class,
        \App\Console\Commands\CleanupDatabase::class,
        \App\Console\Commands\SendReminders::class,
        \App\Console\Commands\ExportGroup::class,
        \App\Console\Commands\ImportGroup::class,
        \App\Console\Commands\EnforceUniqueUsernames::class,
        \App\Console\Commands\EnforceUniqueGroupSlugs::class,
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
        ->everyFiveMinutes();

        $schedule->command('agorakit:checkmailbox')
        ->everyMinute();

        $schedule->command('agorakit:processmessages')
        ->everyMinute();

        $schedule->command('agorakit:sendreminders')
        ->everyFiveMinutes();

        $schedule->command('agorakit:cleanupdatabase')
        ->daily();
    }
}
