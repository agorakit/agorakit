<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\Discussion;
use App\Comment;
use App\Action;
use App\User;
use App\File;
use Carbon\Carbon;

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
    protected $description = 'Cleanup the database, delete forever models older than 30 days';

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
        // Another option would be to make some cleanup of the DB, like removing all unverified users after a while
        // this query returns unverified users with a memberhsip : SELECT * FROM users WHERE id IN (SELECT user_id FROM membership ) and users.verified = 0
        // same for deleted groups : sELECT * FROM groups WHERE id IN (SELECT group_id FROM membership ) and groups.deleted_at is not NULL


        /*
        $model::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->forceDelete();
        */

        // definitely delete deleted groups and all their contents after 30 days

        // get a list of groups
        $groups = Group::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->get();

        foreach ($groups as $group)
        {
            $count = $group->discussions()->delete();
            if ($count) $this->info($count . ' discussions soft deleted in group ' . $group->name);

            $count = $group->actions()->delete();
            if ($count) $this->info($count . ' actions soft deleted in group ' . $group->name);

            $count = $group->files()->delete();
            if ($count) $this->info($count . ' files soft deleted in group ' . $group->name);

            $count = $group->memberships()->delete();
            if ($count) $this->info($count . ' memberships hard deleted in group ' . $group->name);

            $count = $group->invites()->delete();
            if ($count) $this->info($count . ' invites hard deleted in group ' . $group->name);

            $group->forceDelete();
            if ($count) $this->info('Group '. $group->name . ' hard deleted');
        }



        // Handle discussions and their related comments :

        $discussions = Discussion::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->get();

        foreach ($discussions as $discussion)
        {
            // definitely delete comments
            $count = $discussion->comments()->forceDelete();
            if ($count) $this->info($count . ' comments hard deleted on ' . $discussion->name);

            $count = $discussion->forceDelete();
            if ($count) $this->info('Discussion '. $discussion->name . ' hard deleted');
        }

        // Handle actions
        $count = Action::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->forceDelete();


        if ($count) $this->info($count . ' actions hard deleted');


        // Handle files
        $files = File::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->get();


        foreach ($files as $file)
        {
            // definitely delete files on storage
            $file->deleteFromStorage();
            if ($count) $this->info($file->name . ' deleted from storage at ' . $file->path);

            // ...and from DB
            $file->forceDelete();
            if ($count) $this->info($file->name . ' hard deleted');
        }


        // delete soft deleted and unverified users + their content and their memberships after 30 days
        $users = User::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->get();

        $users = $users->merge(User::where('verified', 0)
        ->where('created_at', '<', Carbon::today()->subDays($this->option('days')))
        ->get());

        foreach ($users as $user)
        {
            if ($user->verified == 0)
            {
                $this->info('Unverfied user '. $user->name . '(' . $user->email . ')');
            }

            $count = $user->discussions()->delete();
            if ($count) $this->info($count . ' discussions soft deleted from ' . $user->name);

            $count = $user->actions()->delete();
            if ($count) $this->info($count . ' actions soft deleted from ' . $user->name);

            $count = $user->files()->delete();
            if ($count) $this->info($count . ' files soft deleted from ' . $user->name);

            $count = $user->memberships()->delete();
            if ($count) $this->info($count . ' memberships hard deleted from ' . $user->name);

            $user->forceDelete();
            $this->info('User '. $user->name . '(' . $user->email . ')' . ' hard deleted');
        }





    }
}
