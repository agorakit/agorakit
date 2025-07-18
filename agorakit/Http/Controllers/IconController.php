<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Image;

/**
 * This controller takes care of returning the correct favicon / icon wathever for the app
 */
class IconController extends Controller
{
    public function index(Request $request, $size = 192)
    {
        // validate allowed sizes
        if (!in_array($size, [40, 128, 192, 512])) {
            $size = 192;
        }

        if (Storage::exists('logo.png')) {
            if (Storage::exists('logo-' . $size . '.png')) {
                return response()->file(Storage::path('logo-' . $size . '.png'));
            } else {
                $image = Image::read(Storage::path('logo.png'));
                $image->cover($size, $size);
                $image->save(Storage::path('logo-' . $size . '.png'));
                return response()->file(Storage::path('logo-' . $size . '.png'));
            }
        } else {
            return response()->file(public_path() . '/images/agorakit-icon-' . $size . '.png');
        }
    }
}
