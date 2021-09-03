<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\User;
use App\Membership;
use App\Action;
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


    protected $group;

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


        // create group
        $group = $this->createGroup($data);
        
        // any function called from now on, can (and will) use this group model for it's inner working
        $this->group = $group;


        // handle memberships
        foreach ($data->memberships as $membership) {
            if ($this->createMembership($membership)) {
                $this->info('Created membership for ' . $membership->user->name);
            }
        }


        // handle actions & participations

        foreach ($data->actions as $action) {
            if ($this->createAction($action)) {
                $this->info('Created action called ' . $action->name);
            }
        }



        // handle files

        // handle discussions

        // handle comments

        // handle reactions



        // handle file content

        // handle user's avatar

        // handle group cover


        //dd($group);

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

        $user = $this->createUser($data->user);
        $group->user()->associate($user);
        $group->name = $group->name . ' (imported)';
        
        if ($group->isValid()) {
            $group->save();
            return $group;
        } else {
            $this->error($group->getErrors());
            return false;
        }
    }


    /**
     * Create a new membership based on a json parsed array $data
     */
    function createMembership($data)
    {
        $membership = new Membership;
        $membership->group()->associate($this->group);

        // this is our group member : 
        $user = $this->createUser($data->user);


        $membership->user()->associate($user);

        $membership->created_at = $data->created_at;
        $membership->updated_at = $data->updated_at;
        $membership->config = $data->config;
        $membership->membership = $data->membership;
        $membership->notification_interval = $data->notification_interval;
        $membership->notified_at = $data->notified_at;
        $membership->deleted_at = $data->deleted_at;

        if ($membership->isValid()) {
            $membership->save();
            return $membership;
        } else {
            $this->error($membership->getErrors());
            return false;
        }
    }

    /**
     * Create a new action based on a json parsed array $data
     */
    function createAction($data)
    {
        $action = new Action;

        $user = $this->createUser($data->user);


        $action->group_id = $this->group->id;
        $action->user_id = $user->id;

        $action->created_at = $data->created_at;
        $action->updated_at = $data->updated_at;
        $action->deleted_at = $data->deleted_at;

        $action->name = $data->name;
        $action->body = $data->body;
        $action->start = $data->start;
        $action->stop = $data->stop;
        $action->location = $data->location;
        $action->latitude = $data->latitude;
        $action->longitude = $data->longitude;

        /*
        "attending": [],
        "not_attending": [],
        */

        if ($action->isValid()) {
            $action->save();
            return $action;
        } else {
            $this->error($action->getErrors());
            return false;
        }
    }
}
