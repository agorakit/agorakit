<?php

namespace App\Services;

use App\Action;
use App\Comment;
use App\Discussion;
use App\File;
use App\Group;
use App\Membership;
use App\Reaction;
use App\Tag;
use App\User;
use App\Notifications\AddedToGroup;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Hash;
use Storage;
use ZipArchive;
use \Cviebrock\EloquentSluggable\Services\SlugService;


/**
 * This service imports a group from a file.
 */
class ImportService
{

    public $valid_formats = ['json', 'zip'];


    /**
     * - Do we find a similar group in database?
     */
    private function existing_group($group)
    {
        foreach(Group::all() as $existing_group) {
            if ($existing_group->name == $group->name || $existing_group->name == $group->name . ' (imported)') {
                return "A group already exists with the same name:" . route('groups.show', $existing_group);
            }
            if (count($existing_group->discussions) == count($group->discussions)
                && count($existing_group->actions) == count($group->actions)
                && count($existing_group->files) == count($group->files)) {
                return "A group already exists with same number of data:" . route('groups.show', $existing_group);
            }
        }
    }


    /**
     * - Do some usernames already exist in database?
     */
    private function existing_usernames($group)
    {
        // Compare with already existing usernames, yet email being different
        $existing_usernames = array();
        foreach(User::all() as $existing_user) {
            foreach($group->memberships as $mb) {
                if($mb->user->username == $existing_user->username && $mb->user->email <> $existing_user->email) {
                    $existing_usernames[$mb->user->id] = $mb->user->username;
                }
            }
        }
        return $existing_usernames;
    }


    /**
     * - Search email in imported data
     * - for a given $user_id
     */
    private function get_imported_email($user_id, $group)
    {
        foreach($group->memberships as $mb) {
            if ($mb->user-id == $user_id) {
                return $mb->user-email;
            }
        }
    }

    /**
     * - Return a new user object, just like given $user
     * - yet with a new id and a temporary password.
     */
    private function new_user($user)
    {
        $length = 8;
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = mb_strlen($keyspace, '8bit') - 1;
        $pieces = [];
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        $temporary_password = implode('', $pieces);
        return User::create([
            'id' => null,
            'username' => $user->username,
            'email' => $user->email,
            'name' => $user->name,
            'password' => Hash::make($temporary_password),
            'verified' => 1,
            'preferences' => $user->preferences,
            'location' => $user->location
        ]);
    }


    /**
     * - first step: process import file
     */
    public function import($path)
    {
        // unzip if relevant
        if (pathinfo($path)['extension'] == 'zip') {
            $unzip_path = substr($path, 0, -4);
            $zip = new ZipArchive();
            if ($zip->open(Storage::path($path))!==TRUE) {
                exit("Cannot open " . $path . "\n");
            }
            $zip->extractTo(Storage::path($unzip_path));
            $zip->close();
            $groupfiles = Storage::allFiles($unzip_path);
            foreach($groupfiles as $file) {
                if (basename($file) == 'group.json') {
                    $group_std = json_decode(Storage::get($file), false);
                }
            }
        }
        else { // JSON format
            $group_std = json_decode(Storage::get($path), false);
        }
        // Compare with existing data in database
        $existing_group = $this->existing_group($group_std);
        $existing_usernames = $this->existing_usernames($group_std);
        $group_type = trans('group.open');
        if ($group_std->group_type == Group::CLOSED) {
             $group_type = trans('group.closed');
        }
        if ($group_std->group_type == Group::SECRET) {
             $group_type = trans('group.secret');
        }
        return array(basename($path), $existing_group, $existing_usernames, $group_std->name, $group_type);
    }

    /**
     * - second step: check and import group data
     */
    public function import2($path, $edited_usernames)
    {
        // Retrieve group data (JSON file)
        if (pathinfo($path)['extension'] == 'zip') {
            $unzip_path = substr($path, 0, -4);
            foreach(Storage::allFiles($unzip_path) as $file) {
                if(str_ends_with($file, 'json')) {
                    $json_file = $file;
                    break;
                }
            }
        }
        else {
            $json_file = $path;
        }
        $group = json_decode(Storage::get($json_file), false);
        // Proceed to username replacements if any, create users in that case
        Model::unguard();
        DB::beginTransaction();
        if ($edited_usernames) {
            $still_existing_usernames = array();
            foreach(User::all() as $existing_user) {
                foreach($edited_usernames as $id=>$username) {
                    if ($existing_user->username == $username && $existing_user->email <> $this->get_imported_email($id)) {
                        $still_existing_usernames[$id] = $username;
                    }
                }
            }
            if ($still_existing_usernames) {
                // Go back to intermediate form
                return array(basename($path), null, $still_existing_usernames, $group->name, $group->group_type);
            }
            foreach($edited_usernames as $id=>$username) {
                foreach($group->memberships as $mb) {
                    if ($mb->user->id == $id) {
                        // create new user here, with edited username
                        $mb->user->username = $username;
                        $user_n = $this->new_user($mb->user);
                        if ($user_n->isValid()) {
                            $user_n->save();
                        }
                        else {
                            dump("Error importing user");
                            dump($user_n);
                            DB::rollBack();
                        }
                    }
                }
                /* // Here again, compare with existing usernames in database
                $existing_usernames = $this->existing_usernames($group);
                if ($existing_usernames) {
                    return array(basename($path), null, $existing_usernames);
                } */
            }
        }

        $old_id = $group->id;
        $group_n = null;
        $found_users = array();
        $added_users = array();
        // Insert objects in database
        // Absolutely avoid crushing existing ones
        $group_n = Group::create([
            'id' => null,
            'name' => $group->name . ' (imported)',
            'body' => $group->body,
            'slug' => SlugService::createSlug(Group::class, 'slug', $group->slug),
            'user_id' => Auth::user()->id,
            'color' => $group->color,
            'location' => $group->location,
            'settings' => $group->settings,
            'status' => 0       // do not pin/archive this new group
        ]);
        if ($group_n->isValid()) {
            $group_n->save();
        }
        else {
            dump("Error importing group");
            dump($group_n->getAttributes());
            DB::rollBack();
        }
        $new_id = $group_n->id;
        foreach($group->memberships as $mb) {
            $mb->id = null;
            $mb->group_id = $group_n->id;
            // Case user already in database
            $found_user = User::where('username', $mb->user->username)->where('email', $mb->user->email)->first();
            if ($found_user) {
                $mb->user_id = $found_user->id;
            }
            else {
                $user_n = $this->new_user($mb->user);
                if($user_n->isValid()) {
                    $user_n->save();
                    $mb->user_id = $user_n->id;
                }
                else {
                    dump("Error importing user");
                    dump($user_n); //->username);
                    DB::rollBack();
                }
            }
            $mb_n = Membership::create([
                'id' => null,
                'user_id' => $mb->user_id,
                'group_id' => $group_n->id,
                'membership' => $mb->membership,
                'config' => $mb->config,
                'notification_interval' => $mb->notification_interval,
                'notified_at' => $mb->notified_at
            ]);
            if ($mb_n->isValid()) {
                $mb_n->save();
            }
            else {
                dump("Error importing membership");
                dump($mb_n->getAttributes());
                DB::rollBack();
            }
        }
        foreach($group->actions as $action) {
            $action_user = User::where('username', $action->user->username)->first();
            $action_n = Action::create([
                'id' => null,
                'name' => $action->name,
                'body' => $action->body,
                'user_id' => $action_user->id,
                'group_id' => $group_n->id,
                'start' => $action->start,
                'stop' => $action->stop,
                'location' => $action->location,
                'visibility' => $action->visibility,
                'cover' => $action->cover,
                'created_at' => $action->created_at,
                'updated_at' => $action->updated_at,
                'deleted_at' => $action->deleted_at
            ]);
             if ($action_n->isValid()) {
                $action_n->save();
            }
            else {
                dump("Error importing action");
                dump($action_n->getAttributes());
                DB::rollBack();
            }
            foreach($action->tags as $tag) {
                $action_n->tag($tag);
            }
        }
        foreach($group->discussions as $discussion) {
            $discussion_user = User::where('username', $discussion->user->username)->first();
            $discussion_n = Discussion::create([
                'id' => null,
                'name' => $discussion->name,
                'body' => $discussion->body,
                'user_id' => $discussion_user->id,
                'group_id' => $group_n->id,
                'total_comments' => $discussion->total_comments,
                'status' => $discussion->status,
                'created_at' => $discussion->created_at,
                'updated_at' => $discussion->updated_at,
                'deleted_at' => $discussion->deleted_at
            ]);
            if ($discussion_n->isValid()) {
                $discussion_n->save();
            }
            else {
                dump("Error importing discussion");
                dump($discussion_n->getAttributes());
                DB::rollBack();
            }
            foreach($discussion->comments as $comment) {
                $comment_user = User::where('username', $comment->user->username)->first();
                $comment_n = Comment::create([
                    'id' => null,
                    'body' => $discussion->body,
                    'user_id' => $comment_user->id,
                    'discussion_id' => $discussion_n->id,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'deleted_at' => $comment->deleted_at
                ]);
                if ($comment_n->isValid()) {
                    $comment_n->save();
                }
                else {
                    dump("Error importing comment");
                    dump($comment_n->getAttributes());
                    DB::rollBack();
                }
                foreach($comment->reactions as $reaction) {
                    $reaction_user = User::where('username', $reaction->user->username)->first();
                    $reaction_n = Reaction::create([
                        'id' => null,
                        'user_id' => $reaction_user->id,
                        'reactable_type' => $reaction->reactable_type,
                        'reactable_id' => $comment_n->id,
                        'type' => $reaction->type,
                        'created_at' => $reaction->created_at,
                        'updated_at' => $reaction->updated_at
                    ]);
                    if ($reaction_n->isValid()) {
                        $reaction_n->save();
                    }
                    else {
                        dump("Error importing reaction");
                        dump($reaction_n->getAttributes());
                        DB::rollBack();
                    }
                }
            }
            foreach($discussion->reactions as $reaction) {
                $reaction_user = User::where('username', $reaction->user->username)->first();
                $reaction_n = Reaction::create([
                    'id' => null,
                    'user_id' => $reaction_user->id,
                    'reactable_type' => $reaction->reactable_type,
                    'reactable_id' => $discussion_n->id,
                    'type' => $reaction->type,
                    'created_at' => $reaction->created_at,
                    'updated_at' => $reaction->updated_at
                    ]);
                if ($reaction_n->isValid()) {
                    $reaction_n->save();
                }
                else {
                    dump("Error importing reaction");
                    dump($reaction_n->getAttributes());
                    DB::rollBack();
                }
            }
            foreach($discussion->tags as $tag) {
                $discussion_n->tag($tag->name);
            }
        }
        $files = array(); // Keep track for later
        foreach($group->files as $file) {
            $old_file = $file->id;
            $file_user = User::where('username', $file->user->username)->first();
            $file_n = File::create([
                'id' => null,
                'name' => $file->name,
                'path' => $file->path,
                'original_filename' => $file->original_filename,
                'original_extension' => $file->original_extension,
                'mime' => $file->mime,
                'filesize' => $file->filesize,
                'user_id' => $file_user->id,
                'group_id' => $group_n->id,
                'parent_id' => $file->parent_id,
                'item_type' => $file->item_type,
                'status' => $file->status,
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at,
                'deleted_at' => $file->deleted_at
            ]);
            if ($file_n->isValid()) {
                $file_n->save();
                $files[$old_file] = $file_n->id;
            }
            else {
                dump("Error importing file");
                dump($file_n->getAttributes());
                DB::rollBack();
            }
            foreach($file->tags as $tag) {
                $file_n->tag($tag->name);
            }
        }
        DB::commit();
        Model::reguard();
        if ($group_n && $files && pathinfo($path)['extension']=='zip') {
            Storage::makeDirectory('groups/' . $new_id . '/files');
            foreach(Storage::directories($unzip_path . '/groups/' . $old_id . '/files/') as $dir) {
                $id = array_reverse(explode('/', $dir))[0];
                Storage::move($dir, 'groups/' . $new_id . '/files/' . $files[$id]);
            }
        }
        if ($group_n) {
            foreach($found_users as $id) {
                if ($id<>Auth::user()->id) {
                    $res = User::find($id)->notify(new AddedToGroup($group_n, false));
                }
            }
            foreach($added_users as $id) {
                $user = User::find($id);
                $this->set_temporary_password($user);
                $user->notify(new AddedToGroup($group_n, true));
            }
        }
        return $group_n;
    }
}
