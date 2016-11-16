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
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Group $group)
    {
        // list all files and folders without parent id's (parent_id=NULL)
        //$files = $group->files()->with('user')->orderBy('updated_at', 'desc')->get();
        $files = $group->files()->with('user')->whereNull('parent_id')->orderBy('updated_at', 'desc')->get();

        return view('files.index')
        ->with('files', $files)
        ->with('group', $group)
        ->with('tab', 'files');
    }


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
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Group $group)
    {
        return view('files.create')
        ->with('group', $group)
        ->with('tab', 'files');
    }


    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function createFolder(Group $group)
    {
        return view('files.create_folder')
        ->with('group', $group)
        ->with('tab', 'files');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function storeFolder(Request $request, Group $group)
    {

        $file = new \App\File;
        $file->name = $request->get('folder');

        $file->path = $request->get('folder');

        $file->type == \App\File::FOLDER;

        // add group
        $file->group()->associate($group);

        // add user
        $file->user()->associate(Auth::user());

        if ($file->save())
        {
            flash()->info(trans('messages.ressource_created_successfully'));
            return redirect()->action('FileController@index', [$group->id]);
        }
        else
        {
            dd('folder creation failed');
        }

    }


    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    public function store(Request $request, Group $group)
    {
        try {
            $file = new \App\File();

            // we save it first to get an ID from the database, it will later be used to generate a unique filename.
            $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

            // add group
            $file->group()->associate($group);

            $file->user()->associate(Auth::user());

            // generate filenames and path
            $filepath = '/groups/'.$file->group->id.'/files/';


            $filename = $file->id . '.' . strtolower($request->file('file')->getClientOriginalExtension());

            // resize big images only if they are png, gif or jpeg
            if (in_array ($request->file('file')->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif']))
            {
                Storage::disk('local')->makeDirectory($filepath);
                Image::make($request->file('file'))->widen(1200, function ($constraint) {
                    $constraint->upsize();
                })
                ->save(storage_path().'/app/' . $filepath.$filename);
            }
            else
            {
                // store the file
                Storage::disk('local')->put($filepath.$filename,  file_get_contents($request->file('file')->getRealPath()) );
            }

            // add path and other infos to the file record on DB
            $file->path = $filepath.$filename;
            $file->name = $request->file('file')->getClientOriginalName();
            $file->original_filename = $request->file('file')->getClientOriginalName();
            $file->mime = $request->file('file')->getClientMimeType();

            // save it again
            $file->save();

            if ($request->ajax()) {
                return response()->json('success', 200);
            } else {

                flash()->info(trans('messages.ressource_created_successfully'));
                return redirect()->action('FileController@index', [$group->id]);
            }
        } catch (Exception $e) {

            if ($request->ajax()) {
                return response()->json($e->getMessage(), 400);
            }
            else {
                abort(400, $e->getMessage());
            }
        }
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
        // view depends on file type
        // folder :

        if ($file->item_type == 1)
        {
            return view('files.index')
            ->with('files', $file->children)
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }


        // file
        if ($file->item_type == 0)
        {
            return view('files.show')
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }

        // link
        if ($file->item_type == 2)
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
            //return response()->download($file->path, $file->original_filename);
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
        else
        {
            return redirect('images/extensions/text-file.png');
        }
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


    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    *
    * @return Response
    */
    public function edit(Group $group, File $file)
    {
        return view('files.edit')
        ->with('file', $file)
        ->with('group', $group)
        ->with('tab', 'file');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    *
    * @return Response
    */
    public function update(Request $request, Group $group, File $file)
    {
        $file->retag(implode(',', $request->input('tags')));
        flash()->info(trans('messages.ressource_updated_successfully'));
        return redirect()->action('FileController@index', [$file->group->id]);
    }




    public function destroyConfirm(Request $request, Group $group, File $file)
    {
        if (Gate::allows('delete', $file))
        {
            return view('files.delete')
            ->with('group', $group)
            ->with('file', $file)
            ->with('tab', 'file');
        }
        else
        {
            abort(403);
        }
    }



    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Group $group, File $file)
    {
        if (Gate::allows('delete', $file))
        {
            $file->delete();
            flash()->info(trans('messages.ressource_deleted_successfully'));
            return redirect()->action('FileController@index', [$group->id]);
        }
        else
        {
            abort(403);
        }
    }
}
