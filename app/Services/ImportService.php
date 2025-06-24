<?php

namespace App\Services;

use App\Group;
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
        // Compare with already existing user names
        $existing_usernames = array();
        foreach(User::all() as $existing_user) {
            foreach($group->memberships as $mb) {
                if($mb->user->name == $existing_user->name) {
                    $existing_usernames[$mb->user->id] = $mb->user->name;
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
        list($existing_slug, $existing_usernames) = compare_with_existing($group);
        return array(basename($path), $existing_slug, $existing_usernames);
    }

    /**
     * - second step: check and import group data
     * - parameter $new_data is a list of newly edited slug/usernames
     */
    public function import2($path, $edited_data)
    {
        // Retrieve group data
        $group = new Group(Storage::json($path));
        // Proceed to replacements if any
        if ($edited_data) {
            list($edited_slug, $edited_usernames) = $edited_data;
            if ($edited_slug) {
                $group->slug = $edited_slug;
            }
            foreach($edited_usernames as $id=>$name) {
                foreach($group->memberships as $mb) {
                    if ($mb->user->id == $id) {
                        $mb->user->name = $name;
                    }
                }
            }
        }
        // Once more, compare with existing slug/usernames in database
        list($existing_slug, $existing_usernames) = compare_with_existing($group);
        if ($existing_slug || $existing_usernames) {
            // Go back to intermediate form
            return array($existing_slug, $existing_usernames);
        }
        else {
            // Finally, import the group and return null
            $group->save();
            $group->user()->associate(Auth::user());
        }
    }
}
