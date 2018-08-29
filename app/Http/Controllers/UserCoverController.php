<?php

namespace App\Http\Controllers;


use App\User;
use File;
use Illuminate\Http\Request;
use Image;
use Redirect;
use Storage;


class UserCoverController extends Controller
{
  public function __construct()
  {
    //$this->middleware('show');
  }



  public function show(User $user, $size = 'medium')
  {


    $path = storage_path().'/app/users/'.$user->id.'/cover.jpg';

    if (File::exists($path)) {

      if ($size=='small')
      {
        $cachedImage = Image::cache(function ($img) use ($path) {
          return $img->make($path)->fit(64, 64);
        }, 60000, true);

        return $cachedImage->response();
      }
      if ($size=='medium')
      {
        $cachedImage = Image::cache(function ($img) use ($path) {
          return $img->make($path)->fit(400, 400);
        }, 60000, true);

        return $cachedImage->response();
      }

      if ($size=='large')
      {
        $cachedImage = Image::cache(function ($img) use ($path) {
          return $img->make($path)->fit(800, 800);
        }, 60000, true);

        return $cachedImage->response();
      }
    } else {
      return redirect(url('/images/avatar.svg'));
    }
  }

}
