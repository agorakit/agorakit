<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use Storage;
use ZipArchive;
use File;

class ImportGroup extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:import';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Import a group from an Agorakit export zip file';



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
        // open the zip file

        $zip = new ZipArchive;

        if ($zip->open(storage_path('app/import/group.zip')) === TRUE)
        {

            // extract group content
            $zip->extractTo(storage_path('app/import/tmp'));
            $zip->close();

            // open the json
            $json = Storage::get('import/tmp/group.json');
            $data = json_decode($json);


            // create group
            $group = new Group;
            $group->forceFill([
                //'id' => $data->id, // Here we will decide if we create new id's or not
                'body' => $data->body,
                'name' => $data->name,
                'cover' => $data->cover,
                'user_id' => $data->user_id,
                'created_at' => $data->created_at,
                'group_type' => $data->group_type,
                'color' => $data->color,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'settings' => $data->settings,
                'slug' => $data->slug,
            ]);

            $group->save();



            // create discussions and comments

            // create files


            $this->info('Group imported successfuly');
        }
        else
        {
            $this->error('Could not open zip file');
        }












    }
}
