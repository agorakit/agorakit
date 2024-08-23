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
        $this->middleware('cache.headers:public,max-age=300;etag');
    }

    public function show(User $user, $size = 'medium')
    {
        if ($user->hasCover()) {

            if ($size == 'small') {
                $cachedImage = Image::cache(function ($img) use ($user) {
                    return $img->make($user->getCover())->fit(64, 64);
                }, 5, true);

                return $cachedImage->response();
            }
            if ($size == 'medium') {
                $cachedImage = Image::cache(function ($img) use ($user) {
                    return $img->make($user->getCover())->widen(400);
                }, 5, true);

                return $cachedImage->response();
            }

            if ($size == 'large') {
                $cachedImage = Image::cache(function ($img) use ($user) {
                    return $img->make($user->getCover())->widen(800);
                }, 5, true);

                return $cachedImage->response();
            }
        } else {
            // serve alternative using initials
            $avatar = Avatar::create($user->name)->setBorder(0, false)
                ->setShape('square');

            if ($size == 'small') {
                $avatar->setDimension(48)->setFontSize(20);
            }

            if ($size == 'medium') {
                $avatar->setDimension(400)->setFontSize(200);
            }
            if ($size == 'large') {
                $avatar->setDimension(800)->setFontSize(400);
            }
            return $avatar->getImageObject()->response();
        }
    }
}
