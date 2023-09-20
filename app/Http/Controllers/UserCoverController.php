<?php

namespace App\Http\Controllers;

use App\User;
use File;
use Image;
use Redirect;
use Avatar;
use Storage;

class UserCoverController extends Controller
{
    public function __construct()
    {
         $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(User $user, $size = 'medium')
    {

        // do we have an uploaded avatar ?
        $avatar_path = storage_path().'/app/users/'.$user->id.'/cover.jpg';
        if (!File::exists($avatar_path)) {

            // if not we generate one
            Storage::disk('local')->makeDirectory('users/'.$user->id);
            Avatar::create($user->name)
            ->setDimension(400, 400)
            ->setFontSize(200)
            ->save(storage_path().'/app/users/'.$user->id.'/generated_cover.png');
            $avatar_path = storage_path().'/app/users/'.$user->id.'/generated_cover.png';
        }



        if (File::exists($avatar_path)) {
            if ($size == 'small') {
                $cachedImage = Image::cache(function ($img) use ($avatar_path) {
                    return $img->make($avatar_path)->fit(64, 64);
                }, 5, true);

                return $cachedImage->response();
            }
            if ($size == 'medium') {
                $cachedImage = Image::cache(function ($img) use ($avatar_path) {
                    return $img->make($avatar_path)->widen(400);
                }, 5, true);

                return $cachedImage->response();
            }

            if ($size == 'large') {
                $cachedImage = Image::cache(function ($img) use ($avatar_path) {
                    return $img->make($avatar_path)->widen(800);
                }, 5, true);

                return $cachedImage->response();
            }
        } else {
            // generate an avatar automagically and serve it

            return redirect(url('/images/avatar.svg'));
        }
    }
}
