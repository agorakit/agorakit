<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\Discussion;
use App\Comment;
use App\Event;
use App\Message;
use App\User;
use App\File;
use Carbon\Carbon;
use Venturecraft\Revisionable\Revision;
use Illuminate\Notifications\DatabaseNotification;


class CleanupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:cleanupdatabase {--batch=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup the database, delete forever soft deleted models and unverified users according to data_retention in .env';

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
        // just in case
        if (config('agorakit.data_retention') < 1) {
            $this->error('Data retention is less than one day');
            return false;
        }

        $this->info('Will cleanup the db by deleting all soft deleted items older than ' . config('agorakit.data_retention') . ' days');

        // definitely delete deleted groups and all their contents

        // get a list of groups
        $groups = Group::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get();

        foreach ($groups as $group) {
            $count = $group->discussions()->delete();
            if ($count) $this->info($count . ' discussions soft deleted in group ' . $group->name);

            $count = $group->events()->delete();
            if ($count) $this->info($count . ' events soft deleted in group ' . $group->name);

            $count = $group->files()->delete();
            if ($count) $this->info($count . ' files soft deleted in group ' . $group->name);

            $count = $group->memberships()->delete();
            if ($count) $this->info($count . ' memberships hard deleted in group ' . $group->name);

            $count = $group->invites()->delete();
            if ($count) $this->info($count . ' invites hard deleted in group ' . $group->name);

            $group->deleteCover();
            $group->forceDelete();
            if ($count) $this->info('Group ' . $group->name . ' hard deleted');
        }

        // Handle discussions and their related comments :

        $discussions = Discussion::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get();

        foreach ($discussions as $discussion) {
            // definitely delete comments
            $count = $discussion->comments()->forceDelete();
            if ($count) $this->info($count . ' comments hard deleted on ' . $discussion->name);

            $count = $discussion->forceDelete();
            if ($count) $this->info('Discussion ' . $discussion->name . ' hard deleted');
        }

        // Handle events
        $events = Event::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get();

        foreach ($events as $event) {
            // delete cover files from storage
            $event->deleteCover();

            // ...and from DB
            $event->forceDelete();

            $this->info($event->name . ' deleted from db & storage');
        }

        // Handle files
        $files = File::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get();


        foreach ($files as $file) {
            // definitely delete files on storage
            $file->deleteFromStorage();

            // delete cover files from storage
            $file->deleteCover();

            // ...and from DB
            $file->forceDelete();

            $this->info($file->name . ' deleted from db & storage');
        }


        // delete soft deleted and unverified users + their content and their memberships
        $users = User::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get();

        $users = $users->merge(User::where('verified', 0)
            ->where('created_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->get());

        foreach ($users as $user) {
            if ($user->verified == 0) {
                $this->info('Unverified user ' . $user->name . '(' . $user->email . ')');
            }

            $count = $user->discussions()->delete();
            if ($count) $this->info($count . ' discussions soft deleted from ' . $user->name);

            $count = $user->comments()->delete();
            if ($count) $this->info($count . ' comments soft deleted from ' . $user->name);

            $count = $user->events()->delete();
            if ($count) $this->info($count . ' events soft deleted from ' . $user->name);

            $count = $user->files()->delete();
            if ($count) $this->info($count . ' files soft deleted from ' . $user->name);

            $count = $user->memberships()->delete();
            if ($count) $this->info($count . ' memberships hard deleted from ' . $user->name);

            // delete cover files from storage
            $user->deleteCover();

            // final deletion
            $user->forceDelete();

            $this->info('User ' . $user->name . '(' . $user->email . ')' . ' hard deleted');
        }

        // delete all old revisions, older than double amount of days of retention, just in case
        $count = Revision::where('created_at', '<', Carbon::today()->subDays(config('agorakit.data_retention') * 2))
            ->limit($this->option('batch'))
            ->forceDelete();
        if ($count) $this->info($count . ' revisions deleted');

        // delete all old imported messages
        $count = Message::where('created_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->forceDelete();
        if ($count) $this->info($count . ' inbound mail messages deleted');

        // delete all old notifications
        $count = DatabaseNotification::where('created_at', '<', Carbon::today()->subDays(config('agorakit.data_retention')))
            ->limit($this->option('batch'))
            ->forceDelete();
        if ($count) $this->info($count . ' database notifications deleted');
    }
}
