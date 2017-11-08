<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;
use File;
use Gate;
use Illuminate\Http\Request;
use Image;
use Storage;

class GroupThumbnailController extends Controller
{
    public function cover(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->widen(600);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            return Image::canvas(600, 350)->fill('#cccccc')->response(); // TODO caching or default group image instead
        }
    }

    public function carousel(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(1200,500);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            return Image::canvas(600, 350)->fill('#cccccc')->response(); // TODO caching or default group image instead
        }
    }

    public function avatar(Group $group)
    {
        $path = storage_path().'/app/groups/'.$group->id.'/cover.jpg';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path) {
                return $img->make($path)->fit(128, 128);
            }, 60000, true);

            return $cachedImage->response();
        } else {
            return Image::canvas(600, 350)->fill('#cccccc')->response(); // TODO caching or default group image instead
        }
    }
}
