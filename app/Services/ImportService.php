<?php

namespace App\Services;

use App\Group;
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
    public function import($file)
    {
        // copy file to storage
        //Storage::copy($root . 'group.json', $group->toJson());
        dd($file);
        // unzip if relevant
        $zip = new ZipArchive();
        if ($zip->open($zipfile)!==TRUE) {
            exit("cannot open <$zipfile>\n");
        }
        $groupfiles = Storage::allFiles($root);
        foreach ($groupfiles as $file) {
            if (Storage::exists($file)) {
                $zip->addFile(Storage::disk()->path($file), $file);
            }
        }
        $zip->close();
        //return basename($zipfile);
    }
}
