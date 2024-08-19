<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Image;

/**
* This controller takes care of returning the correct favicon / icon wathever for the app
*/
class IconController extends Controller
{
    public function index(Request $request, $size = 128)
    {

        // validate allowed sizes
        if (!in_array($size, [40, 128, 192, 512])) {
            $size = 128;
        }

        $path = storage_path().'/app/logo.png';

        if (File::exists($path)) {
            $cachedImage = Image::cache(function ($img) use ($path, $size) {
                return $img->make($path)->fit($size, $size);
            }, 5, true);

            return $cachedImage->response();
        } else {
            $path = public_path().'/logo/agorakit-icon-512.png';
            //dd($path);
            $cachedImage = Image::cache(function ($img) use ($path, $size) {
                return $img->make($path)->fit($size, $size);
            }, 5, true);

            return $cachedImage->response();
        }
    }
}
