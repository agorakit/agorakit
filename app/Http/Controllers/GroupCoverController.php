<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;
use File;
use Gate;
use Illuminate\Http\Request;
use Image;
use Storage;


/*
Handle group cover image
*/
class GroupCoverController extends Controller
{

    public function small(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(128, 128);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }

    public function medium(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(400, 300);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }

    public function large(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(800, 600);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            abort(404);
        }
    }


}
