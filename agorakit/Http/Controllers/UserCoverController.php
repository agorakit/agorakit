<?php

namespace Agorakit\Http\Controllers;

use Agorakit\User;
use Avatar;

class UserCoverController extends Controller
{
    public function __construct()
    {
        //$this->middleware('cache.headers:public,max-age=300;etag');
    }

    public function show(User $user, $size = 'original')
    {
        if ($user->hasCover()) {
            return $user->getCover($size);
        } else {
            // serve alternative using initials
            $avatar = Avatar::create($user->name)->setBorder(0, '#aaa')
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
            return response($avatar->getImageObject()->toPng())->header('Content-Type', 'image/png');
        }
    }
}
