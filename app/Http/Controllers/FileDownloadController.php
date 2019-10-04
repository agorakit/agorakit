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
        $this->middleware('public');
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
            return (new Response(Storage::get($file->path), 200))
            ->header('Content-Type', $file->mime)
            ->header('Content-Disposition', 'inline; filename="'.$file->original_filename.'"');
        } else {
            abort(404, 'File not found in storage at '.$file->path);
        }
    }

    /**
     * Returns a square thumbnail of the file.
     */
    public function thumbnail(Group $group, File $file)
    {
        $this->authorize('view', $file);

        if ($file->isImage()) {
            $cachedImage = Image::cache(function ($img) use ($file) {
                return $img->make(storage_path().'/app/'.$file->path)->fit(32, 32);
            }, 60000, true);

            return $cachedImage->response();
        }

        if ($file->isFolder()) {
            return redirect('images/extensions/folder.png');
        }

        return redirect('images/extensions/'.$file->icon().'.svg');
    }

    /**
     * Returns a medium sized preview of the file.
     */
    public function preview(Group $group, File $file)
    {
        $this->authorize('view', $file);

        if ($file->isImage()) {
            $cachedImage = Image::cache(function ($img) use ($file) {
                return $img->make(storage_path().'/app/'.$file->path)->widen(600);
            }, 60000, true);

            return $cachedImage->response();
        }

        return redirect('images/extensions/'.$file->icon().'.svg');
    }

    /**
     * Returns a square svg icon of the file.
     */
    public function icon(Group $group, File $file)
    {
        $this->authorize('view', $file);

        return redirect('images/extensions/'.$file->icon().'.svg');
    }
}
