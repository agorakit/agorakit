<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\User;
use App\Membership;
use App\Action;
use App\Discussion;
use App\Comment;
use App\Reaction;
use App\File;
use Storage;
use ZipArchive;
use \Cviebrock\EloquentSluggable\Services\SlugService;

use Illuminate\Support\Facades\Storage as FacadesStorage;


/**
 * Import content command
 * 
 * This might look a bit ugly and unoptimized but at least it's easy to understand what's going on.
 * 
 *  Import expects a zip file in [your app installation]/storage/app/exports/[group id]/group.zip
 * 
 */
class ImportGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:import {group : the ID of the group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a group from an Agorakit export zip file';


    protected $group;
    protected $zip;

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

        $zipfilename = storage_path('app/exports/' . $this->argument('group') . '/group.zip');

        $this->zip = new ZipArchive;

        if ($this->zip->open($zipfilename) === TRUE) {

            $this->line('Extracting zip file : ' . $zipfilename);

            // extract group content
            $this->zip->extractTo(storage_path('app/imports/' . $this->argument('group')));
            $this->zip->close();
        } else {
            $this->error('Could not open zip file or file not found at ' . $zipfilename);
            die();
        }

        $this->newLine();


        // parse json
        $data = json_decode(Storage::get('imports/' . $this->argument('group') . '/group.json'));


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

        $this->newLine();


        // handle actions & participations
        foreach ($data->actions as $actionData) {
            if ($this->createAction($actionData)) {
                $this->info('Created action called ' . $actionData->name);
            }
        }

        $this->newLine();


        // handle discussions, comments and reactions
        foreach ($data->discussions as $discussionData) {
            if ($this->createDiscussion($discussionData)) {
                $this->info('Created discussion called ' . $discussionData->name);
            }
        }

        $this->newLine();



        // handle files
        foreach ($data->files as $fileData) {
            if ($this->createFile($fileData)) {
                $this->info('Created file called ' . $fileData->name);
            }
        }

        $this->newLine();


        // handle user's avatar

        // handle group cover


        // delete tmp files

        if (Storage::deleteDirectory('imports/' . $this->argument('group'))) {
            $this->info('Temp files deleted');
        } else {
            $this->error('Could not delete temp files');
        }

        $this->line('Group imported successfuly');
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

        if ($user->isValid()) {
            $user->save();
            return $user;
        } else {
            $this->error($user->getErrors());
            return false;
        }
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




        if ($action->isValid()) {
            $action->save();

            // Now the action is saved, we can handle attending and not attending users

            if (is_array($data->attending)) {
                foreach ($data->attending as $userAttendingData) {
                    $userAttending = $this->createUser($userAttendingData);
                    $action->attending()->save($userAttending);
                }
            }

            if (is_array($data->not_attending)) {
                foreach ($data->not_attending as $userNotAttendingData) {
                    $userNotAttending = $this->createUser($userNotAttendingData);
                    $action->notAttending()->save($userNotAttending);
                }
            }

            return $action;
        } else {
            $this->error($action->getErrors());
            return false;
        }
    }


    /**
     * Create a new discussion based on a json parsed array $data
     */
    function createDiscussion($data)
    {
        $discussion = new Discussion;

        $user = $this->createUser($data->user);


        $discussion->group_id = $this->group->id;
        $discussion->user_id = $user->id;

        $discussion->created_at = $data->created_at;
        $discussion->updated_at = $data->updated_at;
        $discussion->deleted_at = $data->deleted_at;

        $discussion->name = $data->name;
        $discussion->body = $data->body;
        $discussion->status = $data->status;
        $discussion->total_comments = $data->total_comments;


        if ($discussion->isValid()) {
            $discussion->save();

            // now we have a discussion let's handle the comments
            foreach ($data->comments as $commentData) {
                $this->createComment($discussion, $commentData);
            }


            return $discussion;
        } else {
            $this->error($discussion->getErrors());
            return false;
        }
    }


    /**
     * Create a new comment based on a json parsed array $data
     */
    function createComment(Discussion $discussion, $data)
    {
        $comment = new Comment;

        $user = $this->createUser($data->user);

        $comment->user_id = $user->id;

        $comment->created_at = $data->created_at;
        $comment->updated_at = $data->updated_at;
        $comment->deleted_at = $data->deleted_at;

        $comment->body = $data->body;

        if ($comment->isValid()) {
            $comment->save();
            $discussion->comments()->save($comment);

            // now we have a comment let's handle reactions
            foreach ($data->reactions as $reactionData) {
                $this->createReaction($comment, $reactionData);
            }


            return $comment;
        } else {
            $this->error($discussion->getErrors());
            return false;
        }
    }


    /**
     * Create a new reaction based on a json parsed array $data
     */
    function createReaction($model, $data)
    {
        $reaction = new Reaction;

        $user = $this->createUser($data->user);

        $reaction->user_id = $user->id;

        $reaction->created_at = $data->created_at;
        $reaction->updated_at = $data->updated_at;

        $reaction->type = $data->type;

        $reaction->reactable_type = $data->reactable_type;
        $reaction->reactable_id = $model->id;

        if ($reaction->isValid()) {
            $reaction->save();
            return $reaction;
        } else {
            $this->error($reaction->getErrors());
            return false;
        }
    }



    /**
     * Create a new file based on a json parsed array $data
     */
    function createFile($data)
    {
        $file = new File;

        $user = $this->createUser($data->user);


        $file->group_id = $this->group->id;
        $file->user_id = $user->id;

        $file->created_at = $data->created_at;
        $file->updated_at = $data->updated_at;
        $file->deleted_at = $data->deleted_at;

        $file->name = $data->name;
        $file->path = $data->path;
        $file->mime = $data->mime;
        $file->original_filename = $data->original_filename;
        $file->original_extension = $data->original_extension;
        $file->filesize = $data->filesize;
        $file->item_type = $data->item_type;
        $file->parent_id = $data->parent_id;
        $file->status = $data->status;



        if ($file->isValid()) {
            $file->save();

            // now we have a file let's handle the content


            return $file;
        } else {
            $this->error($file->getErrors());
            return false;
        }
    }
}
