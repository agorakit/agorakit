<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\File;
use Carbon\Carbon;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:deletefiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files from storage after 30 days of deletion in database';

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
        $files = File::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30)->toDateTimeString())
            ->get();

            dd($files);
    }
}
