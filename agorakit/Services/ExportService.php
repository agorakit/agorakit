<?php

namespace Agorakit\Services;

use Agorakit\Group;
use Auth;
use Route;
use Storage;
use ZipArchive;

/**
 * This service exports a group on demand.
 * For use by authorized only (ie group admins).
 */
class ExportService
{

    public $valid_formats = ['csv', 'json'];


    /**
     * - all : admin overview of all discussions for example
     */
    public function export($group, $format='json')
    {
        // load related content. I know it cascades but this way I have a complete list of models I need to process
        $group->load([
            'user',
            'memberships.user',
            'actions',
            'actions.user',
            'discussions',
            'discussions.user',
            'discussions.comments',
            'discussions.comments.user',
            'discussions.comments.reactions',
            'discussions.comments.reactions.user',
            'discussions.reactions',
            'discussions.reactions.user',
            'files',
            'files.user',
            'tags'
        ]);

        // group storage root path
        $root = 'groups/' . $group->id . '/';

        // save group json to storage
        Storage::put($root . 'group.json', $group->toJson());

        flash('Json export has been put into ' . $root . 'group.json');

        // create a zip file with the whole group folder
        $zipdir = Storage::disk('tmp')->url('');
        $zipfile = tempnam($zipdir, '');
        $zip = new ZipArchive();
        if ($zip->open($zipfile, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zipfile>\n");
        }
        $groupfiles = Storage::allFiles($root);
        foreach ($groupfiles as $file) {
            if (Storage::exists($file)) {
                $zip->addFile(Storage::disk()->path($file), $file);
            }
        }
        $zip->close();
        return basename($zipfile);
    }
}
