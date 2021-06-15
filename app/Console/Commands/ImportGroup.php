<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\User;
use Storage;
use ZipArchive;
use File;
use \Cviebrock\EloquentSluggable\Services\SlugService;

use Illuminate\Support\Facades\Storage as FacadesStorage;


/**
 * Import content command
 * 
 * This might look a bit ugly and unoptimized but at least it's easy to understand what's going on.
 */
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

        //$data = json_decode(Storage::get('imports/group.json'));
        $data = json_decode(Storage::get('exports/1/group.json')); // for tests


        $group = $this->createGroup($data);
        $user = $this->createUser($data->user);
        $group->user()->associate($user);
        $group->save();

        dd($group);


        //dd (Storage::get)
        // open the zip file

        /*
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
*/
    }

    /**
     * Create a user based on data or load an existing user if already there
     * $data is supposed to be complete enough and pass user validation else this will fail awfully.
     */
    public function createUser($data)
    {
        $user = User::firstOrNew(['email' => $data->email]);
        if ($user->exists) {
            return $user;
        }

        $user->name = $data->name;
        $user->email = $data->email;
        $user->verified = $data->verified;
        $user->created_at = $data->created_at;
        $user->updated_at = $data->updated_at;
        $user->deleted_at = $data->deleted_at;
        $user->body = $data->body;
        $user->preferences = (array) $data->preferences;
        $user->admin = $data->admin;
        $user->address = $data->address;
        $user->latitude = $data->latitude;
        $user->longitude = $data->longitude;
        $user->username = $data->username;

        $user->save();

        return $user;
    }

    /**
     * Create a new group based on passed data
     * Does not save the group yet
     */
    function createGroup($data)
    {
        $group = new Group;
        $group->name = $data->name;
        $group->body = $data->body;
        $group->cover = $data->cover;
        $group->color = $data->color;
        $group->group_type = $data->group_type;
        $group->address = $data->address;
        $group->latitude = $data->latitude;
        $group->longitude = $data->longitude;
        $group->settings = (array) $data->settings;


        // regenerate a slug just in case it's already taken
        $slug = SlugService::createSlug(Group::class, 'slug', $data->slug);

        $group->slug = $slug;
        $group->status = $data->status;

        return $group;
    }
}
