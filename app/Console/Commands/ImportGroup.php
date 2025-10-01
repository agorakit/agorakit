<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;
use App\User;
use App\Membership;
use App\CalendarEvent;
use App\Discussion;
use App\Comment;
use App\Reaction;
use App\File;
use Storage;
use ZipArchive;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\Str;

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
    protected $description = 'Import a group from an Agorakit export json file';


    protected $group;
    protected $zip;
    protected $originalGroupId;

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

        $group_id = $this->argument('group');


        // parse json
        $data = json_decode(Storage::get('groups/' . $group_id . '/group.json'));


        $group = $this->createGroup($data);


        // any function called from now on, can (and will) use this group model for it's inner working
        $this->group = $group;

        // store original group id, we'll need it
        $this->originalGroupId = $data->id;


        // handle memberships
        foreach ($data->memberships as $membership) {
            if ($this->createMembership($membership)) {
                $this->info('Created membership for ' . $membership->user->name);
            }
        }

        $this->newLine();


        // handle events & participations
        foreach ($data->calendarevents as $eventData) {
            if ($this->createCalendarEvent($eventData)) {
                $this->info('Created event called ' . $eventData->name);
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

        $this->line('Group imported successfuly');
    }


    /**
     * Create a new group based on passed data
     * Does not save the group yet
     */
    protected function createGroup($data)
    {
        $group = new Group();
        $group->id = $data->id;
        $group->name = $data->name;
        $group->body = $data->body;
        $group->cover = $data->cover;
        $group->color = $data->color;
        $group->group_type = $data->group_type;
        $group->location = $data->location;
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

        if ($group->exists) {
            $this->error('A group with the same id exist on this install this is not a good idea to import again.
                Yes we need UUIDs to solve this issue');
            die();
        }


        if ($group->isValid()) {
            $group->save();
            return $group;
        } else {
            $this->error($group->getErrors());
            return false;
        }
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
        $user->location = $data->location;
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
     * Create a new membership based on a json parsed array $data
     */
    protected function createMembership($data)
    {
        $membership = new Membership();
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
     * Create a new event based on a json parsed array $data
     */
    protected function createCalendarEvent($data)
    {
        $event = new CalendarEvent();

        $user = $this->createUser($data->user);


        $event->group_id = $this->group->id;
        $event->user_id = $user->id;

        $event->created_at = $data->created_at;
        $event->updated_at = $data->updated_at;
        $event->deleted_at = $data->deleted_at;

        $event->name = $data->name;
        $event->body = $data->body;
        $event->start = $data->start;
        $event->stop = $data->stop;
        $event->location = $data->location;
        $event->latitude = $data->latitude;
        $event->longitude = $data->longitude;


        if ($event->isValid()) {
            $event->save();

            // Now the event is saved, we can handle attending and not attending users

            if (is_array($data->attending)) {
                foreach ($data->attending as $userAttendingData) {
                    $userAttending = $this->createUser($userAttendingData);
                    $event->attending()->save($userAttending);
                }
            }

            if (is_array($data->not_attending)) {
                foreach ($data->not_attending as $userNotAttendingData) {
                    $userNotAttending = $this->createUser($userNotAttendingData);
                    $event->notAttending()->save($userNotAttending);
                }
            }

            return $event;
        } else {
            $this->error($event->getErrors());
            return false;
        }
    }


    /**
     * Create a new discussion based on a json parsed array $data
     */
    protected function createDiscussion($data)
    {
        $discussion = new Discussion();

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
    protected function createComment(Discussion $discussion, $data)
    {
        $comment = new Comment();

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
    protected function createReaction($model, $data)
    {
        $reaction = new Reaction();

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
    protected function createFile($data)
    {
        $file = new File();

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

            // curently the files must be manually moved to the correct directory.
            // Which is /storage/app/groups/(group_id)/files


            return $file;
        } else {
            $this->error($file->getErrors());
            return false;
        }
    }
}
