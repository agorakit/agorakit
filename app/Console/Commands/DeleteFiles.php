<?php

namespace App\Console\Commands;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:deletefiles
  {{--force : force confirmation of deletion}}';

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

        $filesize = File::onlyTrashed()
    ->where('deleted_at', '<=', Carbon::now()->subDays(30)->toDateTimeString())
    ->sum('filesize');

        foreach ($files as $file) {
            $this->line($file->name.' takes '.sizeForHumans($file->filesize));
        }

        $this->info('This would save '.sizeForHumans($filesize));

        $really_delete = false;
        if ($this->option('force')) {
            $really_delete = true;
        } else {
            $confirm = $this->confirm('Do you want to delete the files from storage (no undo!) ?');
            if ($confirm) {
                $really_delete = true;
            }
        }

        if ($really_delete) {
            foreach ($files as $file) {
                if ($file->deleteFromStorage()) {
                    $this->line($file->name.' deleted from storage');
                } else {
                    $this->error($file->name.' NOT deleted from storage');
                }

                // also delete the db entry
                $file->forceDelete();
            }
        }
    }
}
