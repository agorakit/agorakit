<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use Storage;
use ZipArchive;

class ExportGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:export {group : the ID of the group} {--passwords : wether hashed user password must be included in the dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a group to a zip file';



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
        // load the group
        $group = \App\Group::findOrFail($this->argument('group'));

        // load related content. I know it cascades but this way I have a complete list of models I need to process
        $group->load([
            'user',
            'memberships.user',
            'calendarevents',
            'calendarevents.user',
            'discussions',
            'discussions.user',
            'discussions.comments',
            'discussions.comments.user',
            'discussions.comments.reactions',
            'discussions.comments.reactions.user',
            'discussions.reactions',
            'discussions.reactions.user',
            'files',
            'files.user',
            'tags'
        ]);

        if ($this->option('passwords')) {
            // add the hasshed password to the json file. Handle with care! TODO explain security implications
            foreach ($group->memberships as $membership) {
                $membership->user->makeVisible('password');
            }
            $this->error('All hashed user passwords have been added to the json file. Handle with care!!!');
        } else {
            $this->info('No user passwords have been added to the json file. Users will have to reset their password on the target instance');
        }

        // group storage root path
        $root = 'groups/' . $group->id . '/';

        // save group json to storage
        Storage::put($root . 'group.json', $group->toJson());

        $this->info('Json export has been put into ' . $root . 'group.json');

        // create a zip file with the whole group folder
        $zipdir = Storage::disk('tmp')->url('');
        $zipfile = $zipdir . 'group-' . $group->id . '-files.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipfile, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zipfile>\n");
        }
        $groupfiles = Storage::allFiles($root);
        foreach ($groupfiles as $file) {
            if (Storage::exists($file)) {
                $zip->addFile(Storage::disk()->path($file), $file);
            }
        }
        $zip->close();

        $this->info('Group export has been put into ' . $zipfile);
    }
}
