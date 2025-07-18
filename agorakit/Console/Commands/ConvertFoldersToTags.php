<?php

namespace Agorakit\Console\Commands;

use Illuminate\Console\Command;

class ConvertFoldersToTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:convertfolderstotags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all folders to the new tag based system. Add the tags represneting parent folder to each file';

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
        foreach (\Agorakit\File::all() as $file) {
            $this->info('Checking if there is something to do for file '.$file->name.' ('.$file->id.')');

            if ($file->isFile()) {
                foreach ($file->getAncestors() as $parent) {
                    $file->tag($parent->name);
                }
                $this->info('Tagged file '.$file->name);
            }
        }
    }
}
