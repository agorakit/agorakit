<?php

namespace App\Services;

use App\Group;
use App\Membership;
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
                $mb = $mb;
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
        }
        dd("fin");
	return $group;
    }
}
