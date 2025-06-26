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
use Auth;
use Route;
use Storage;
use ZipArchive;

/**
 * This service imports a group from a file.
 */
class ImportService
{

    public $valid_formats = ['json', 'zip'];


    /**
     * - Does group slug already exist in database?
     * - Do some usernames already exist in database?
     */
    private function compare_with_existing($group)
    {
        // Compare with already existing group slugs
        $existing_slug = false;
        foreach(Group::all() as $existing_group) {
            if($group->slug == $existing_group->slug) {
                $existing_slug = $existing_group->slug;
            }
        }
        // Compare with already existing usernames, yet email being different
        $existing_usernames = array();
        foreach(User::all() as $existing_user) {
            foreach($group->memberships as $mb) {
                if($mb->user->username == $existing_user->username && $mb->user->email <> $existing_user->email) {
                    $existing_usernames[$mb->user->id] = $mb->user->username;
                }
            }
        }
        return array($existing_slug, $existing_usernames);
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
        list($existing_slug, $existing_usernames) = $this->compare_with_existing($group);
        return array(basename($path), $existing_slug, $existing_usernames);
    }

    /**
     * - second step: check and import group data
     * - parameter $new_data is a list of newly edited slug/usernames
     */
    public function import2($path, $edited_data)
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
        if ($edited_data) {
            list($edited_slug, $edited_usernames) = $edited_data;
            if ($edited_slug) {
                $group->slug = $edited_slug;
            }
            foreach($edited_usernames as $id=>$username) {
                foreach($group->memberships as $mb) {
                    if ($mb->user->id == $id) {
                        $mb->user->username = $username;
                    }
                }
                foreach($group->actions as $action) {
                    if ($action->user->id == $id) {
                        $action->user->username = $username;
        		//dd($action);
                    }
                }
                foreach($group->discussions as $discussion) {
                    if ($discussion->user->id == $id) {
                        $discussion->user->username = $username;
        		//dd($discussion);
                    }
                    foreach($discussion->comments as $comment) {
                        if ($comment->user->id == $id) {
                            $comment->user->username = $username;
        		    //dd($comment);
                        }
                        foreach($comment->reactions as $reaction) {
                            if ($reaction->user->id == $id) {
                            $reaction->user->username = $username;
        		    //dd($reaction);
                            }
                        }
                    }
                    foreach($discussion->reactions as $reaction) {
                        if ($reaction->user->id == $id) {
                            $reaction->user->username = $username;
        		    //dd($reaction);
                        }
                    }
                }
                foreach($group->files as $file) {
                    if ($file->user->id == $id) {
                        $file->user->username = $username;
        		//dd($file);
                    }
                }
            }
        }
        // Once more, compare with existing slug/usernames in database
        list($existing_slug, $existing_usernames) = $this->compare_with_existing($group);
        if ($existing_slug || $existing_usernames) {
            // Go back to intermediate form
            return array($existing_slug, $existing_usernames);
        }
        else {
            // Insert objects in database
            // Absolutely avoid crushing existing ones
            $group->id = null;
            $group_n = Group::create($group->getAttributes());
            $group_n->user()->associate(Auth::user());
            $group_n->save();
            foreach($group->memberships as $mb) {
                $user = clone $mb->user;
                $user->id = null;
                $user->email = "ok@agorakit.org";
                $user_n = User::create($user->getAttributes());
                $user_n->verified = 1;
                $user_n->save();
                $mb->id = null;
                $mb->group()->associate($group_n);
                $mb->user()->associate($user_n);
                $mb_n = Membership::create($mb->getAttributes());
                $mb_n->save();
            }
            foreach($group->actions as $action) {
                $action->id = null;
                $action->group()->associate($group_n);
                $user_n = User::where('username', $action->user->username)->first();
                $action->user()->associate($user_n);
                $action_n = Action::create($action->getAttributes());
                $action_n->save();
            }
            foreach($group->discussions as $discussion) {
                $discussion->id = null;
                $discussion->group()->associate($group_n);
                $user_n = User::where('username', $discussion->user->username)->first();
                $discussion->user()->associate($user_n);
                $discussion_n = Discussion::create($discussion->getAttributes());
                $discussion_n->save();
                foreach($discussion->comments as $comment) {
                    $comment->id = null;
                    $comment->group()->associate($group_n);
                    $user_n = User::where('username', $comment->user->username)->first();
                    $comment->user()->associate($user_n);
                    $comment_n = Comment::create($comment->getAttributes());
                    $comment_n->save();
                    foreach($comment->reactions as $reaction) {
                        $reaction->id = null;
                        $reaction->group()->associate($group_n);
                        $user_n = User::where('username', $reaction->user->username)->first();
                        $reaction->user()->associate($user_n);
                        $reaction_n = Reaction::create($reaction->getAttributes());
                        $reaction_n->save();
                    }
                }
                foreach($discussion->reactions as $reaction) {
                    $reaction->id = null;
                    $reaction->group()->associate($group_n);
                    $user_n = User::where('username', $reaction->user->username)->first();
                    $reaction->user()->associate($user_n);
                    $reaction_n = Reaction::create($reaction->getAttributes());
                    $reaction_n->save();
                }
            }
            foreach($group->files as $file) {
                $file->id = null;
                $file->group()->associate($group_n);
                $user_n = User::where('username', $file->user->username)->first();
                $file->user()->associate($user_n);
                $file_n = File::create($file->getAttributes());
                $file_n->save();
            }
            foreach($group->tags as $tag) {
                $tag->id = null;
                $tag->group()->associate($group_n);
                $tag_n = Tag::create($tag->getAttributes());
                $tag_n->save();
            }
        }
        //dd("fin");
        return $group_n;
    }
}
