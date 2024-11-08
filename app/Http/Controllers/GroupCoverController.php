<?php

namespace App\Http\Controllers;

use App\Group;
use File;
use Image;

/*
Handle group cover image
*/

class GroupCoverController extends Controller
{

    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(Group $group, $size = 'original')
    {
        if ($group->hasCover()) {
            return $group->getCover($size);
        } else {
            abort(404);
        }
    }

    public function small(Group $group)
    {
        $this->authorize('view', $group);

        $path = storage_path() . '/app/groups/' . $group->id . '/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(128, 128);
            }, 5, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }

    public function medium(Group $group)
    {
        $this->authorize('view', $group);

        $path = storage_path() . '/app/groups/' . $group->id . '/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(400, 300);
            }, 5, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }

    public function large(Group $group)
    {
        $this->authorize('view', $group);

        $path = storage_path() . '/app/groups/' . $group->id . '/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->widen(800);
            }, 5, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }
}
