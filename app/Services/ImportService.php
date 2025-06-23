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
     * - all : admin overview of all discussions for example
     */
    public function import($path)
    {
        // unzip if relevant
        $unzip_path = substr($path, 0, -4);
        if (str_ends_with($path, 'zip')) {
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
        // Compare with already existing group slugs
        foreach(Group::all() as $existing_group) {
            if($group->slug == $existing_group->slug) {
                print($existing_group->slug);
            }
        }
        // Compare with already existing user names
        $to_modify = collect();
        foreach(User::all() as $existing_user) {
            foreach($group->memberships as $mb) {
                if($mb->user->name == $existing_user->name) {
                    //print($existing_user->name);
                    $to_modify->push($mb->user);
                }
            }
        }
        dd($to_modify);
    }
}
