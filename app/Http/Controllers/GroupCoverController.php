<?php

namespace App\Http\Controllers;

use App\Models\Group;
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

    public function small(Group $group)
    {
        $this->authorize('view', $group);

        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

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

        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

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

        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(800, 600);
            }, 5, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }
}
