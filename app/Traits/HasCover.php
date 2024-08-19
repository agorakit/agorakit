<?php

namespace App\Traits;


use Image;
use File;
use Storage;
use Illuminate\Http\Request;

/** 
 * This trait allows any model to have an image cover 
 * - stored in [model type]/[model id]/cover.jpg 
 * TODO refactor this to store covers in the proper group, if item belongs to a group
 * TODO use proper storage abstraction instead of using storage_path()
 */
trait HasCover
{

    public function getCoverPath()
    {
        return storage_path() . '/app/' . $this->type . '/' . $this->id . '/cover.jpg';
    }

    public function hasCover()
    {
        return File::exists($this->getCoverPath());
    }

    // size can be small medium large square
    public function getCover($size = 'medium')
    {
        $path = $this->getCoverPath();

        if (File::exists($path)) {
            if ($size == 'small') {
                $cachedImage = Image::cache(function ($img) use ($path) {
                    return $img->make($path)->fit(128, 128);
                }, 5, true);
            }

            if ($size == 'medium') {
                $cachedImage = Image::cache(function ($img) use ($path) {
                    return $img->make($path)->fit(400, 300);
                }, 5, true);
            }
            if ($size == 'large') {
                $cachedImage = Image::cache(function ($img) use ($path) {
                    return $img->make($path)->widen(1024);
                }, 5, true);
            }

            if ($size == 'square') {
                $cachedImage = Image::cache(function ($img) use ($path) {
                    return $img->make($path)->fit(512, 512);
                }, 5, true);
            }
            return $cachedImage->response();
        } else {
            return abort(404);
        }
    }

    public function setCoverFromRequest(Request $request)
    {
        if ($request->hasFile('cover')) {
            Storage::disk('local')->makeDirectory($this->class . '/' . $this->id);
            return   Image::make($request->file('cover'))->widen(1024)->save(storage_path() . '/app/' . $this->class . '/' . $this->id . '/cover.jpg');
        }
        return false;
    }
}
