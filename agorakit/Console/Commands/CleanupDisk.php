<?php

namespace Agorakit\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Storage;

/***************************
 * Cleanup temporary storage
 * Will delete all files older
 * that 2 hrs when the command
 * is launched
 *
****************************/


class CleanupDisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:cleanupdisk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup the disk, remove temporary files older than 2 hours';

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
        $this->info('Will delete all files in temporary storage older than 2 hours');

        collect(Storage::disk('tmp')->allFiles())->each(function ($file) {
            $file_last_modified = Carbon::parse(Storage::disk('tmp')->lastModified($file));
            if ($file_last_modified->diffInHours(now()) >= 2) {
                Storage::disk('tmp')->delete($file);
            }
        });
    }
}
