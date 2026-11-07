<?php

namespace App\Http\Controllers;

use App\File;
use App\Group;
use Illuminate\Http\Response;
use Image;
use Storage;

class FileDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    /**
    * Returns the specified file for downloading.
    */
    public function download(Group $group, File $file)
    {
        $this->authorize('view', $file);

        if ($file->isLink()) {
            return redirect($file->path);
        }

        if (Storage::exists($file->path)) {
            // Only let safe, non-active types render inline; force download for
            // everything else (e.g. HTML/SVG) and send nosniff, so a user-uploaded
            // file cannot execute script in the application origin.
            $inlineTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'application/pdf'];
            $disposition = in_array(strtolower((string) $file->mime), $inlineTypes, true) ? 'inline' : 'attachment';

            return (new Response(Storage::get($file->path), 200))
            ->header('Content-Type', $file->mime)
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Content-Disposition', $disposition . '; filename="' . $file->original_filename . '"');
        } else {
            abort(404, 'File not found in storage at ' . $file->path);
        }
    }

    /**
    * Returns a square thumbnail of the file.
    */
    public function thumbnail(Group $group, File $file)
    {
        $this->authorize('view', $file);

        if ($file->isImage()) {
            return $file->getCover('small');
        }


        if ($file->isFolder()) {
            return redirect('images/extensions/folder.svg');
        }

        return redirect('images/extensions/' . $file->icon() . '.svg');
    }

    /**
    * Returns a medium sized preview of the file.
    */
    public function preview(Group $group, File $file)
    {
        $this->authorize('view', $file);

        if ($file->isImage()) {
            return $file->getCover('medium');
        }

        return redirect('images/extensions/' . $file->icon() . '.svg');
    }

    /**
    * Returns a square svg icon of the file.
    */
    public function icon(Group $group, File $file)
    {
        $this->authorize('view', $file);

        return redirect('images/extensions/' . $file->icon() . '.svg');
    }
}
