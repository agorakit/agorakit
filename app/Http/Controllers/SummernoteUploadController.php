<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Intervention\Image\Laravel\ImageResponseFactory as Image;

class SummernoteUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function upload(Request $request)
    {
        // check if the request comes as an ajax-request
        if ($request->ajax()) {
            // validate if the file is an image to protect against bad files uploaded
            $request->validate([
                'file' => 'required|image',
            ]);

            // store the file
            $file = $request->file('file');
            $store = $file->store('summer-uploads', 'public');

            // resize the file, optional (intervention/image package)
            $image = Image::make(public_path('storage/' . $store));
            if($image->width() > 600) {
                $image->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->save();

            //respons to the editor
            return response()->json($request->root() . '/storage/' . $store, 200, [], JSON_UNESCAPED_SLASHES);
        }

        return App::abort(404);
    }
}
