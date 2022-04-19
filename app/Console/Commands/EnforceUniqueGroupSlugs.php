<?php

namespace App\Console\Commands;

use App\Group;
use DB;
use Illuminate\Console\Command;

class EnforceUniqueGroupSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:enforceuniquegroupslugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enforce each group has a unique slug';

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
        $duplicates = DB::table('groups')
        ->select('id', 'slug', DB::raw('COUNT(*) as `count`'))
        ->groupBy('slug')
        ->havingRaw('COUNT(*) > 1')
        ->get();

        foreach ($duplicates as $duplicate) {
            $group = Group::withTrashed()->find($duplicate->id);
            $group->slug = null;
            $group->save();
            $this->line($group->name.' modified with slug like : '.$group->slug);
        }
    }
}
