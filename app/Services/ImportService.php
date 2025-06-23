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
    public function import($path)
    {
        // unzip if relevant
        if (str_ends_with($path, 'zip')) {
            $zip = new ZipArchive();
            if ($zip->open(Storage::path($path))!==TRUE) {
                exit("Cannot open " . $path . "\n");
            }
            $zip->extractTo(substr(Storage::path($path), 0, -4));
            $zip->close();
        }
        else { // JSON format
            Storage::copy($path, substr($path, 0, -4) . '/groups/1/' . basename($path));
        }
    }
}
