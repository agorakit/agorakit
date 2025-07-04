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
            if ($existing_group->name == $group->name) {
                return "A group already exists with the same name! id=" . $existing_group->id;
            }
            if (count($existing_group->discussions) == count($group->discussions)
                && count($existing_group->actions) == count($group->actions)
                && count($existing_group->files) == count($group->files)) {
                return "A group already exists with same number of data! id=" . $existing_group->id;
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
     * - Make password and notify users
     */
    private function set_temporary_password($user)
    {
        $length = 8;
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = mb_strlen($keyspace, '8bit') - 1;
        $pieces = [];
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        $temporary_password = implode('', $pieces);
        $user->password = Hash::make($temporary_password);
        $user->save();
    }


    /**
     * - first step: process import file
     */
    public function import($path)
    {
        // unzip if relevant
        if (str_ends_with($path, 'zip')) {
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
                    $group = new Group(Storage::json($file));
                }
            }
        }
        else { // JSON format
            $group = new Group(Storage::json($path));
        }
        // Compare with existing data in database
        $existing_group = $this->existing_group($group);
        $existing_usernames = $this->existing_usernames($group);
        return array(basename($path), $existing_group, $existing_usernames);
    }

    /**
     * - second step: check and import group data
     */
    public function import2($path, $edited_usernames)
    {
        // Retrieve group data (JSON file)
        if (str_ends_with($path, 'zip')) {
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
        $group = new Group(Storage::json($json_file));
        // Proceed to replacements if any
        if ($edited_usernames) {
            foreach($edited_usernames as $id=>$username) {
                foreach($group->memberships as $mb) {
                    if ($mb->user->id == $id) {
                        $mb->user->username = $username;
                    }
                }
                // Here again, compare with existing usernames in database
                $existing_usernames = $this->existing_usernames($group);
                if ($existing_usernames) {
                    // Go back to intermediate form
                    return array(basename($path), null, $existing_usernames);
                }
            }
            // Continue replacements
            foreach($edited_usernames as $id=>$username) {
                foreach($group->actions as $action) {
                    if ($action->user->id == $id) {
                        $action->user->username = $username;
                    }
                }
                foreach($group->discussions as $discussion) {
                    if ($discussion->user->id == $id) {
                        $discussion->user->username = $username;
                    }
                    foreach($discussion->comments as $comment) {
                        if ($comment->user->id == $id) {
                            $comment->user->username = $username;
                        }
                        foreach($comment->reactions as $reaction) {
                            if ($reaction->user->id == $id) {
                            $reaction->user->username = $username;
                            }
                        }
                    }
                    foreach($discussion->reactions as $reaction) {
                        if ($reaction->user->id == $id) {
                            $reaction->user->username = $username;
                        }
                    }
                }
                foreach($group->files as $file) {
                    if ($file->user->id == $id) {
                        $file->user->username = $username;
                    }
                }
            }
        }

        DB::beginTransaction();
        $old_id = $group->id;
        $group_n = null;
        $found_users = array();
        $added_users = array();
        // Insert objects in database
        // Absolutely avoid crushing existing ones
        $group_o = clone $group;
        $group->id = null;
        $group->name = $group->name . ' (imported)';
        $group->slug = SlugService::createSlug(Group::class, 'slug', $group->slug);
        $group_n = Group::create($group->getAttributes());
        $group_n->user()->associate(Auth::user());
        $group_n->location = $group->location;
        if ($group_n->isValid()) {
            $group_n->save();
        }
        else {
            dump("Error importing group");
            dump($group_n->getAttributes());
            DB::rollBack();
        }
        $new_id = $group_n->id;
        foreach($group_o->memberships as $mb) {
            $mb->id = null;
            $mb->group()->associate($group_n);
            $user = clone $mb->user;
            // Case user already in database
            $found_user = User::where('username', $user->username)->where('email', $user->email)->first();
            if ($found_user) {
                $mb->user()->associate($found_user);
                if (!in_array($found_user->id, $found_users)) {
                    $found_users[] = $found_user->id;
                }
            }
            else {
                $user->id = null;
                $user_n = User::create($user->getAttributes());
                $user_n->location = $user->location;
                $user_n->verified = 1;
                if($user_n->isValid()) {
                    $user_n->save();
                    $added_users[] = $user_n->id;
                }
                else {
                    dump("Error importing user");
                    dump($user_n->username);
                    DB::rollBack();
                }
                $mb->user()->associate($user_n);
            }
            $mb_n = Membership::create($mb->getAttributes());
            if ($mb_n->isValid()) {
                $mb_n->save();
            }
            else {
                dump("Error importing membership");
                dump($mb_n->getAttributes());
                DB::rollBack();
            }
        }
        foreach($group_o->actions as $action) {
            $action->id = null;
            $action->group()->associate($group_n);
            $user_n = User::where('username', $action->user->username)->first();
            $action->user()->associate($user_n);
            $action_n = Action::create($action->getAttributes());
            $action_n->location = $action->location;
            $action_n->created_at = $action->created_at;
            $action_n->updated_at = $action->updated_at;
            $action_n->deleted_at = $action->deleted_at;
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
        foreach($group_o->discussions as $discussion) {
            $discussion_o = clone $discussion;
            $discussion->id = null;
            $discussion->group()->associate($group_n);
            $user_n = User::where('username', $discussion->user->username)->first();
            $discussion->user()->associate($user_n);
            $discussion_n = Discussion::create($discussion->getAttributes());
            $discussion_n->created_at = $discussion->created_at;
            $discussion_n->updated_at = $discussion->updated_at;
            $discussion_n->deleted_at = $discussion->deleted_at;
            if ($discussion_n->isValid()) {
                $discussion_n->save();
            }
            else {
                dump("Error importing discussion");
                dump($discussion_n->getAttributes());
                DB::rollBack();
            }
            foreach($discussion_o->comments as $comment) {
                $comment->id = null;
                $comment->discussion()->associate($discussion_n);
                $user_n = User::where('username', $comment->user->username)->first();
                $comment->user()->associate($user_n);
                $comment_n = Comment::create($comment->getAttributes());
                $comment_n->created_at = $comment->created_at;
                $comment_n->updated_at = $comment->updated_at;
                $comment_n->deleted_at = $comment->deleted_at;
                if ($comment_n->isValid()) {
                    $comment_n->save();
                }
                else {
                    dump("Error importing comment");
                    dump($comment_n->getAttributes());
                    DB::rollBack();
                }
                foreach($comment->reactions as $reaction) {
                    $reaction->id = null;
                    $user_n = User::where('username', $reaction->user->username)->first();
                    $reaction->user()->associate($user_n);
                    $reaction->reactable_id = $comment_n->id;
                    $reaction_n = Reaction::create($reaction->getAttributes());
                    $reaction_n->created_at = $reaction->created_at;
                    $reaction_n->updated_at = $reaction->updated_at;
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
            foreach($discussion_o->reactions as $reaction) {
                $reaction->id = null;
                $user_n = User::where('username', $reaction->user->username)->first();
                $reaction->user()->associate($user_n);
                $reaction->reactable_id = $discussion_n->id;
                $reaction_n = Reaction::create($reaction->getAttributes());
                $reaction_n->created_at = $reaction->created_at;
                $reaction_n->updated_at = $reaction->updated_at;
                if ($reaction_n->isValid()) {
                    $reaction_n->save();
                }
                else {
                    dump("Error importing reaction");
                    dump($reaction_n->getAttributes());
                    DB::rollBack();
                }
            }
            foreach($discussion_o->tags as $tag) {
                $discussion_n->tag($tag);
            }
        }
        $files = array();
        foreach($group_o->files as $file) {
            $old_file = $file->id;
            $file->id = null;
            $file->group()->associate($group_n);
            $user_n = User::where('username', $file->user->username)->first();
            $file->user()->associate($user_n);
            $file_n = File::create($file->getAttributes());
            $file_n->created_at = $file->created_at;
            $file_n->updated_at = $file->updated_at;
            $file_n->deleted_at = $file->deleted_at;
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
                $file_n->tag($tag);
            }
        }
        DB::commit();
        if ($group_n && $files) {
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
