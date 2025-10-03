<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class ConvertFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:convertfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the files from the old flat path based storage to the new storage,
        putting back their initial filename and moving the file to public directory.
        Use this **once** if you already have files on your install and if your install is older than november 2016.';

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
        foreach (\App\Group::all() as $group) {
            $this->info('Checking if there is something to convert for group ' . $group->name . ' (' . $group->id . ')');
            foreach ($group->files as $file) {
                if ($file->isFile()) {
                    if (Storage::disk('local')->has($file->path)) {
                        $source = Storage::disk('local')->get($file->path);
                        Storage::disk('public')->put('groups/' . $group->id . '/' . $file->name, $source);
                        $this->info('Copied file ' . $file->name);
                    } else {
                        $this->info('File not found ' . $file->name);
                    }
                }
            }
        }
    }
}
