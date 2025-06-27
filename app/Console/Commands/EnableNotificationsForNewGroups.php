<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\Discussion;
use App\Comment;
use App\Action;
use App\Message;
use App\User;
use App\File;
use Carbon\Carbon;
use Venturecraft\Revisionable\Revision;
use Illuminate\Notifications\DatabaseNotification;

class EnableNotificationsForNewGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:enablenotificationsfornewgroups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If a group was created today, enable notifications (in case they were disabled at creation time).';

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
        $this->info('Will enable notifications for newly created groups');
        $groups = Group::whereBetween('created_at', [Carbon::now()->subHours(1441), Carbon::now()->subMinutes(59)])
            ->get();

        foreach ($groups as $group) {
            $group->notifications_enabled = true;
            $group->save();
        }

    }
}
