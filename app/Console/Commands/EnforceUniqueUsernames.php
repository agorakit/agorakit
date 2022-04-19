<?php

namespace App\Console\Commands;

use App\User;
use DB;
use Illuminate\Console\Command;

class EnforceUniqueUsernames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:enforceuniqueusernames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enforce each user has a unique username';

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
        $duplicates = DB::table('users')
        ->select('id', 'username', DB::raw('COUNT(*) as `count`'))
        ->groupBy('username')
        ->havingRaw('COUNT(*) > 1')
        ->get();

        foreach ($duplicates as $duplicate) {
            $user = User::withTrashed()->find($duplicate->id);
            $user->username = null;
            $user->save();
            $this->line($user->name.' modified with username like : '.$user->username);
        }
    }
}
