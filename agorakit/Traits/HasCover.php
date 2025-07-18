<?php

namespace Agorakit\Traits;

use Intervention\Image\Laravel\Facades\Image;
use Storage;
use Illuminate\Http\Request;
use Throwable;

/**
 * This trait allows any model to have an image cover
 * Storage :
 * - users in users/[id]/cover|small|medium|large.jpg
 * - groups is groups/[id]/cover|small|medium|large.jpg
 * - actions in groups/[id]/actions/[id]/cover|small|medium|large.jpg
 * - files in groups/[id]/files/[id]/small|medium|large.jpg
 *
 * Available sizes :
 * - small
 * - medium
 * - large
 * - square
 */
trait HasCover
{
    // list of available sizes (for validation)
    private $sizes = ['small', 'medium', 'large', 'square'];

    /**
     * Return the path where the covers and thumbnails are stored for this model
     */
    public function getCoverPath()
    {
        if ($this->getType() == "group") $path =  'groups/' . $this->id . '/';
        if ($this->getType() == "user") $path =  'users/' . $this->id  . '/';
        if ($this->getType() == "file") $path =  'groups/' . $this->group->id . '/files/' . $this->id . '/';
        if ($this->getType() == "action") $path =  'groups/' . $this->group->id . '/actions/' . $this->id . '/';

        return $path;
    }

    /**
     * Returns wether a cover.jpg file has been stored for this model, which means a thumbnail can be derived
     */
    public function hasCover()
    {
        return Storage::exists($this->getCoverPath() . 'cover.jpg');
    }

    /**
     * Returns the cover file response
     */
    public function getCover($size = 'medium')
    {
        if (in_array($size, $this->sizes)) {
            if (Storage::exists($this->getCoverPath() . $size . '.jpg')) {
                return response()->file(Storage::path($this->getCoverPath() . $size . '.jpg'));
            } else {
                $this->generateThumbnails();
                return response()->file(Storage::path($this->getCoverPath() . $size . '.jpg'));
            }
        }
        return abort(404);
    }


    /**
     * Save the cover for this model from the request. Request should contain a "cover" field
     */
    public function setCoverFromRequest(Request $request)
    {
        if ($request->hasFile('cover')) {
            try {
                Storage::makeDirectory($this->getCoverPath());
                $image = Image::read($request->file('cover'));
                $image->save(Storage::path($this->getCoverPath() . 'cover.jpg'));
                $this->generateThumbnails();
            } catch (Throwable $e) {
                report($e);
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Generate all thumbnails sizes for this model
     */
    public function generateThumbnails()
    {
        // if the model is a file, we take directly the uploaded file as a base to generate the thumbnail
        if ($this->getType() == "file") {
            if ($this->isImage()) {
                $image = Image::read(Storage::path($this->path));
            }
        } else {
            $image = Image::read(Storage::path($this->getCoverPath() . 'cover.jpg'));
        }

        // make cover path if needed
        Storage::makeDirectory($this->getCoverPath());

        $image->scaleDown(width: 1024);
        $image->save(Storage::path($this->getCoverPath() . 'large.jpg'));

        $image->cover(400, 300);
        $image->save(Storage::path($this->getCoverPath() . 'medium.jpg'));

        $image->cover(64, 64);
        $image->save(Storage::path($this->getCoverPath() . 'small.jpg'));
    }

    /**
     * Delete all cover variations for this model
     */
    public function deleteCover()
    {
        if ($this->getType() <> "file") {
            Storage::delete($this->getCoverPath() . 'cover.jpg');
        }

        Storage::delete($this->getCoverPath() . 'small.jpg');
        Storage::delete($this->getCoverPath() . 'medium.jpg');
        Storage::delete($this->getCoverPath() . 'large.jpg');
    }
}
