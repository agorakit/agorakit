<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\Comment;
use App\Models\Discussion;
use App\Models\File;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Venturecraft\Revisionable\Revision;

class CleanupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:cleanupdatabase {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup the database, delete forever soft deleted models and unverified users older than 30 days';

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
        if ($this->option('days') < 1) {
            return false;
        }

        // definitely delete deleted groups and all their contents

        // get a list of groups
        $groups = Group::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
            ->get();

        foreach ($groups as $group) {
            $count = $group->discussions()->delete();
            if ($count) {
                $this->info($count.' discussions soft deleted in group '.$group->name);
            }

            $count = $group->actions()->delete();
            if ($count) {
                $this->info($count.' actions soft deleted in group '.$group->name);
            }

            $count = $group->files()->delete();
            if ($count) {
                $this->info($count.' files soft deleted in group '.$group->name);
            }

            $count = $group->memberships()->delete();
            if ($count) {
                $this->info($count.' memberships hard deleted in group '.$group->name);
            }

            $count = $group->invites()->delete();
            if ($count) {
                $this->info($count.' invites hard deleted in group '.$group->name);
            }

            $group->forceDelete();
            if ($count) {
                $this->info('Group '.$group->name.' hard deleted');
            }
        }

        // Handle discussions and their related comments :

        $discussions = Discussion::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
            ->get();

        foreach ($discussions as $discussion) {
            // definitely delete comments
            $count = $discussion->comments()->forceDelete();
            if ($count) {
                $this->info($count.' comments hard deleted on '.$discussion->name);
            }

            $count = $discussion->forceDelete();
            if ($count) {
                $this->info('Discussion '.$discussion->name.' hard deleted');
            }
        }

        // Handle actions
        $count = Action::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
            ->forceDelete();

        if ($count) {
            $this->info($count.' actions hard deleted');
        }

        // Handle files
        $files = File::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
            ->get();

        foreach ($files as $file) {
            // definitely delete files on storage
            $file->deleteFromStorage();
            if ($count) {
                $this->info($file->name.' deleted from storage at '.$file->path);
            }

            // ...and from DB
            $file->forceDelete();
            if ($count) {
                $this->info($file->name.' hard deleted');
            }
        }

        // delete soft deleted and unverified users + their content and their memberships
        $users = User::onlyTrashed()
            ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
            ->get();

        $users = $users->merge(User::where('verified', 0)
            ->where('created_at', '<', Carbon::today()->subDays($this->option('days')))
            ->get());

        foreach ($users as $user) {
            if ($user->verified == 0) {
                $this->info('Unverfied user '.$user->name.'('.$user->email.')');
            }

            $count = $user->discussions()->delete();
            if ($count) {
                $this->info($count.' discussions soft deleted from '.$user->name);
            }

            $count = $user->comments()->delete();
            if ($count) {
                $this->info($count.' comments soft deleted from '.$user->name);
            }

            $count = $user->actions()->delete();
            if ($count) {
                $this->info($count.' actions soft deleted from '.$user->name);
            }

            $count = $user->files()->delete();
            if ($count) {
                $this->info($count.' files soft deleted from '.$user->name);
            }

            $count = $user->memberships()->delete();
            if ($count) {
                $this->info($count.' memberships hard deleted from '.$user->name);
            }

            $user->forceDelete();
            $this->info('User '.$user->name.'('.$user->email.')'.' hard deleted');
        }

        // delete all old revisions, older than double amount of days of retention, just in case
        $count = Revision::where('created_at', '<', Carbon::today()->subDays($this->option('days') * 2))->forceDelete();
        if ($count) {
            $this->info($count.' revisions deleted');
        }

        // delete all old imported messages
        $count = Message::where('created_at', '<', Carbon::today()->subDays(7))->forceDelete();
        if ($count) {
            $this->info($count.' inbound mail messages deleted');
        }

        // delete all old notifications
        $count = DatabaseNotification::where('created_at', '<', Carbon::today()->subDays($this->option('days')))->forceDelete();
        if ($count) {
            $this->info($count.' database notifications deleted');
        }
    }
}
