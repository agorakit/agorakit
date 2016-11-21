<?php

namespace App\Http\Controllers;

use App\Group;
use App\File;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Image;
use Auth;
use Storage;
use Gate;
use Config;
use Log;

//class FileController extends \Barryvdh\Elfinder\ElfinderController
class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('member', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('cache', ['only' => ['index', 'show']]);
        $this->middleware('public', ['only' => ['index', 'gallery', 'thumbnail', 'preview']]);
    }


    /**
    * Simple callback catcher
    *
    * @param  string   $cmd       command name
    * @param  array    $result    command result
    * @param  array    $args      command arguments from client
    * @param  object   $elfinder  elFinder instance
    * @param  object   $volume    current volume instance
    * @return void|true
    **/
    public function elfinderRmCallback($cmd, &$result, $args, $elfinder, $volume) {
        if ($cmd == 'rm')
        {
            $result = false;
            return false;
        }

    }

    public static function checkAccess($attr, $path, $data, $volume)
    {

        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        :  null;                                    // else elFinder decide it itself
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Group $group)
    {

        $locale = str_replace("-",  "_", Config::get('app.locale'));

        // if (!file_exists($this->app['path.public'] . "/$dir/js/i18n/elfinder.$locale.js"))
        // {
        // $locale = false;
        // }

        return view('files.elfinder')
        ->with('tab', 'files')
        ->with('dir', 'packages/barryvdh/elfinder')
        ->with('group', $group)
        ->with('locale', $locale);
    }



/*************************** this is deprecated and candidate for removal soon, since we switch to elfinder for all file operations **********************/

    public function gallery(Group $group)
    {
        $files = $group->files()
        ->with('user')
        ->where('mime', 'like', 'image/jpeg')
        ->orWhere('mime', 'like', 'image/png')
        ->orWhere('mime', 'like', 'image/gif')
        ->orderBy('updated_at', 'desc')
        ->paginate(100);


        return view('files.gallery')
        ->with('files', $files)
        ->with('group', $group)
        ->with('tab', 'files');
    }




    /**
    * Display the specified resource.
    *
    * @param int $id
    *
    * @return Response
    */
    public function show(Group $group, File $file)
    {

        if ($file->parent_id)
        {
            $parent_id = $file->parent_id;
        }
        else
        {
            $parent_id = null;
        }

        // view depends on file type
        // folder :

        if ($file->isFolder())
        {
            return view('files.index')
            ->with('files', $file->children)
            ->with('parent_id', $parent_id)
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }


        // file
        if ($file->isFile())
        {
            return view('files.show')
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }

        // link
        if ($file->isLink())
        {
            return view('files.link')
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }
    }


    /**
    * Display the specified resource.
    *
    * @param int $id
    *
    * @return Response
    */
    public function download(Group $group, File $file)
    {
        if (Storage::exists($file->path)) {
            return (new Response(Storage::get($file->path), 200))
            ->header('Content-Type', $file->mime)
            ->header('Content-Disposition', 'inline; filename="' . $file->original_filename . '"');
        } else {
            abort(404, 'File not found in storage at ' . $file->path);
        }
    }

    public function thumbnail(Group $group, File $file)
    {
        if (in_array($file->mime, ['image/jpeg', 'image/png', 'image/gif']))
        {
            $cachedImage = Image::cache(function($img) use ($file) {
                return $img->make(storage_path().'/app/'.$file->path)->fit(32, 32);
            }, 60000, true);

            return $cachedImage->response();
        }

        if ($file->isFolder())
        {
            return redirect('images/extensions/folder.png');
        }



        return redirect('images/extensions/text-file.png');

    }


    public function preview(Group $group, File $file)
    {

        if (in_array($file->mime, ['image/jpeg', 'image/png', 'image/gif']))
        {
            $cachedImage = Image::cache(function($img) use ($file) {
                return $img->make(storage_path().'/app/'.$file->path)->fit(250,250);
            }, 60000, true);

            return $cachedImage->response();
        }
        else
        {
            return redirect('images/extensions/text-file.png');
        }
    }

}
