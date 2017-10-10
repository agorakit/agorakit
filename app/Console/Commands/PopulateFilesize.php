<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Storage;

class PopulateFilesize extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:populatefilesize';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Set filesize in the files table using real filesize from the filesystem. Use this if you installed agorakit before november 2017';

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
        foreach (\App\File::all() as $file)
        {
            if (Storage::exists($file->path))
            {
                $file->filesize = Storage::size($file->path);
                $file->save();
            }
        }

    }
}
