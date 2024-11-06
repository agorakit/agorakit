<?php

namespace App\Traits;


use Intervention\Image\Laravel\Facades\Image;
use File;
use Storage;
use Illuminate\Http\Request;

/** 
 * 
 *
 * 
 * This trait allows any model to have an image cover 
 * - stored in [model type]/[model id]/cover.jpg 
 * TODO refactor this to store covers in the proper group, if item belongs to a group
 * TODO use proper storage abstraction instead of using storage_path()
 * 
 * Available sizes : 
 * - small
 * - medium
 * - large
 * - square
 */
trait HasCover
{

    private $sizes = ['small', 'medium', 'large', 'square'];


    public function getCoverPath($size = null)
    {
        if (get_class($this) == 'App\User') $type = 'user';
        elseif (get_class($this) == 'App\File') $type = 'file';
        else $type = 'unknown';

        $path =  'covers/' . $type . '/' . $this->id . '/';

        if ($size == 'original') {
            return $path . 'cover.jpg';
        }

        if (in_array($size, $this->sizes)) {
            return $path . $size . '.jpg';
        }

        return $path;
    }

    public function hasCover()
    {
        return Storage::exists($this->getCoverPath('original'));
    }

    // size can be small medium large square
    public function getCover($size = 'medium')
    {
        if (in_array($size, $this->sizes) && Storage::exists($this->getCoverPath($size))) {
            return response()->file(Storage::path($this->getCoverPath($size)));
        }
        return abort(404);
    }

    public function setCoverFromRequest(Request $request)
    {
        if ($request->hasFile('cover')) {
            Storage::makeDirectory($this->getCoverPath());
            $image = Image::read($request->file('cover'));

            $image->save(Storage::path($this->getCoverPath()));

            $image->scaleDown(width: 1024);
            $image->save(Storage::path($this->getCoverPath('large')));

            $image->scaleDown(width: 512);
            $image->save(Storage::path($this->getCoverPath('medium')));

            $image->scaleDown(width: 256);
            $image->save(Storage::path($this->getCoverPath('small')));
        }
        return false;
    }
}
