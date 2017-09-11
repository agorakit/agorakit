<?php

namespace App\Http\Controllers;

use App\File;
use App\Group;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Image;
use Storage;
use Validator;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('member', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('cache', ['only' => ['download', 'thumbnail', 'preview']]);
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
        $files = $group->files()
        ->where('item_type', '<>', \App\File::FOLDER)
        ->with('user')
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('files.index')
        ->with('parent_id', null)
        ->with('files', $files)
        ->with('all_tags', \App\File::allTags())
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
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Group $group, File $file)
    {
        // file
        if ($file->isFile()) {
            return view('files.show')
            ->with('file', $file)
            ->with('group', $group)
            ->with('tab', 'files');
        }

        // link
        if ($file->isLink()) {
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
        if (Gate::allows('download', $file)) {
            if ($file->isLink())
            {
                return redirect($file->path);
            }

            if (Storage::exists($file->path)) {
                return (new Response(Storage::get($file->path), 200))
                ->header('Content-Type', $file->mime)
                ->header('Content-Disposition', 'inline; filename="'.$file->original_filename.'"');
            } else {
                abort(404, 'File not found in storage at '.$file->path);
            }
        } else {
            abort(404, 'Unauthorized');
        }
    }

    public function thumbnail(Group $group, File $file)
    {
        if (Gate::allows('download', $file)) {
            if (in_array($file->mime, ['image/jpeg', 'image/png', 'image/gif'])) {
                $cachedImage = Image::cache(function ($img) use ($file) {
                    return $img->make(storage_path().'/app/'.$file->path)->fit(32, 32);
                }, 60000, true);

                return $cachedImage->response();
            }

            if ($file->isFolder()) {
                return redirect('images/extensions/folder.png');
            }
        }

        return redirect('images/extensions/text-file.png');
    }

    public function preview(Group $group, File $file)
    {
        if (Gate::allows('download', $file)) {
            if (in_array($file->mime, ['image/jpeg', 'image/png', 'image/gif'])) {
                $cachedImage = Image::cache(function ($img) use ($file) {
                    return $img->make(storage_path().'/app/'.$file->path)->fit(250, 250);
                }, 60000, true);

                return $cachedImage->response();
            }
        }

        return redirect('images/extensions/text-file.png');
    }

    /************************** Files handling methods **********************/

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Group $group)
    {
        return view('files.create')
        ->with('all_tags', \App\File::allTags())
        ->with('group', $group)
        ->with('tab', 'files');
    }

    public function createLink(Request $request, Group $group)
    {
        return view('files.createlink')
        ->with('all_tags', \App\File::allTags())
        ->with('group', $group)
        ->with('tab', 'files');
    }

    /**
     * Store a new file.².
     *
     * @return Response
     */
    public function store(Request $request, Group $group)
    {
        try {
            if ($request->file('files')) {
                foreach ($request->file('files') as $uploaded_file) {
                    $file = new File();

                    // we save it first to get an ID from the database, it will later be used to generate a unique filename.
                    $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

                    // add group, user and tags
                    $file->group()->associate($group);
                    $file->user()->associate(Auth::user());
                    $file->tag($request->get('tags'));

                    // generate filenames and path
                    $filepath = '/groups/'.$file->group->id.'/files/';

                    $filename = $file->id.'.'.strtolower($uploaded_file->getClientOriginalExtension());

                    // resize big images only if they are png, gif or jpeg
                    if (in_array($uploaded_file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                        Storage::disk('local')->makeDirectory($filepath);
                        Image::make($uploaded_file)->widen(1200, function ($constraint) {
                            $constraint->upsize();
                        })
                        ->save(storage_path().'/app/'.$filepath.$filename);
                    } else {
                        // store the file
                        Storage::disk('local')->put($filepath.$filename, file_get_contents($uploaded_file->getRealPath()));
                    }

                    // add path and other infos to the file record on DB
                    $file->path = $filepath.$filename;
                    $file->name = $uploaded_file->getClientOriginalName();
                    $file->original_filename = $uploaded_file->getClientOriginalName();
                    $file->mime = $uploaded_file->getClientMimeType();

                    // save it again
                    $file->save();
                }

                flash()->info(trans('messages.ressource_created_successfully'));
                if (isset($parent)) {
                    return redirect()->action('FileController@show', [$group, $parent]);
                } else {
                    return redirect()->action('FileController@index', $group);
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json($e->getMessage(), 400);
            } else {
                abort(400, $e->getMessage());
            }
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
        ->with('all_tags', \App\File::allTags())
        ->with('model_tags', $file->tags)
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
        $file->retag($request->get('tags'));

        if ($file->save()) {
            flash()->info(trans('messages.ressource_updated_successfully'));

            return redirect()->action('FileController@index', [$group]);
        } else {
            flash()->info(trans('messages.ressource_not_updated_successfully'));

            return redirect()->back();
        }
    }

    public function destroyConfirm(Request $request, Group $group, File $file)
    {
        if (Gate::allows('delete', $file)) {
            return view('files.delete')
            ->with('group', $group)
            ->with('file', $file)
            ->with('tab', 'file');
        } else {
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
        if (Gate::allows('delete', $file)) {
            $file->delete();
            flash()->info(trans('messages.ressource_deleted_successfully'));

            return redirect()->action('FileController@index', [$group]);
        } else {
            abort(403);
        }
    }

    /**
     * Store the folder in the file DB.
     *
     * @return Response
     */
    public function storeLink(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'link'  => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        }

        $file = new File();
        $file->name = $request->get('title');
        $file->path = $request->get('link');
        $file->item_type = File::LINK;
        // add group
        $file->group()->associate($group);

        // add user
        $file->user()->associate(Auth::user());

        if ($file->save()) {
            // handle tags
            $file->tag($request->get('tags'));

            flash()->info(trans('messages.ressource_created_successfully'));

            return redirect()->action('FileController@index', $group);
        } else {
            flash()->error(trans('messages.ressource_not_created_successfully'));

            return redirect()->back();
        }
    }
}
