<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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


        // TODO set a retention period in days (default to 30)

        /*
        $model::onlyTrashed()
        ->where('deleted_at', '<', Carbon::today()->subDays($this->option('days')))
        ->forceDelete();
        */

        // definitely delete deleted groups and all their contents after 30 days

        // delete unverified users and their memberships after 30 days

        // definitely delete content after 30 days

        // definitely delete files on storage after 30 days


    }
}
