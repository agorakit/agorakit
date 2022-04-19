<?php

namespace App\Console\Commands;

use App\Models\Group;
use File;
use Illuminate\Console\Command;
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
        $group = \App\Models\Group::findOrFail($this->argument('group'));

        // load related content. I know it cascades but this way I have a complete list of models I need to process
        $group->load([
            'user',
            'memberships.user',
            'actions',
            'actions.user',
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
            'tags',
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

        // save group json to storage
        Storage::disk('local')->makeDirectory('exports/'.$group->id);
        Storage::put('exports/'.$group->id.'/group.json', $group->toJson());

        $this->info('Json export has been put into '.'exports/'.$group->id.'/group.json');

        // make a zip file of everything
        $zip = new ZipArchive;

        if ($zip->open(storage_path('app/exports/'.$group->id.'/group.zip'), ZipArchive::CREATE) === true) {
            // add json
            if ($zip->addFile(storage_path('app/exports/'.$group->id.'/group.json'), 'group.json')) {
                $this->line('Added group.json dump to archive');
            } else {
                $this->error('Json dump could not be added to archive');
            }

            // handle groups files
            foreach ($group->files as $file) {
                if ($zip->addFile($file->storagePath(), '/files/'.$file->id.'/'.basename($file->path))) {
                    $this->line('Added '.$file->path.' to group files export');
                } else {
                    $this->error('Failed to add '.$file->path.' to group files export');
                }
            }

            // handle groups cover
            $files = File::files(storage_path('app/groups/'.$group->id));

            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }

            // handle groups users covers
            foreach ($group->users as $user) {
                $zip->addFile(storage_path('app/users/'.$user->id.'/cover.jpg'), 'users/'.$user->id.'/cover.jpg');
                $zip->addFile(storage_path('app/users/'.$user->id.'/thumbnail.jpg'), 'users/'.$user->id.'/thumbnail.jpg');
            }

            $zip->close();

            $this->info('Group exported successfuly to '.storage_path('app/exports/'.$group->id.'/group.zip'));
        } else {
            $this->error('Could not create zip file');
        }
    }
}
