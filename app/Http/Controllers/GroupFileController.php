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

class GroupFileController extends Controller
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
    public function index(Request $request, Group $group)
    {
        // Validate query string, I feel it's better for sql injection prevention  :-)
        if (!in_array($request->get('dir'), ['asc', 'desc', null])) {
            abort(404, 'invalid sort order');
        }

        if (!in_array($request->get('sort'), ['created_at', 'name', 'filesize', null])) {
            abort(404, 'invalid sort type');
        }


        // Generate a list of tags from this group :
        // TODO optimize me
        // One day, groups will have their own, fixed tag list
        $files = $group->files()
        ->where('item_type', '<>', \App\File::FOLDER)
        ->with('tags')
        ->get();

        $tags = [];
        foreach ($files as $file) {
            foreach ($file->tags as $tag) {
                $tags[$tag->tag_id] = $tag->name;
            }
        }

        // Query depending of the request
        // filter by tags and sort order
        $tag = $request->get('tag');

        $files = $group->files()
        ->where('item_type', '<>', \App\File::FOLDER)
        ->with('user')
        ->with('tags')
        ->with('group')
        ->orderBy($request->get('sort', 'created_at'), $request->get('dir', 'desc'))
        ->when($tag, function ($query) use ($tag) {
            return $query->withAnyTags($tag);
        })
        ->paginate(20);



        return view('files.index')
        ->with('files', $files)
        ->with('group', $group)
        ->with('tags', $tags)
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
        return view('files.show')
        ->with('file', $file)
        ->with('group', $group)
        ->with('tab', 'files');
    }


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
    * Store a new file.
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

                    if ($request->get('tags')) {
                        $file->tag($request->get('tags'));
                    }

                    // generate filenames and path
                    $filepath = '/groups/'.$file->group->id.'/files/';

                    // simplified filename
                    $filename = $file->id.'-'.str_slug($uploaded_file->getClientOriginalName()).'.'.strtolower($uploaded_file->getClientOriginalExtension());

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
                    $file->filesize =  $uploaded_file->getClientSize();

                    // save it again
                    $file->save();

                    // update activity timestamp on parent items
                    $group->touch();
                    \Auth::user()->touch();
                }

                flash(trans('messages.ressource_created_successfully'));
                if (isset($parent)) {
                    return redirect()->route('groups.files.show', [$group, $parent]);
                } else {
                    return redirect()->route('groups.files.index', $group);
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
        if ($request->get('tags')) {
            $file->retag($request->get('tags'));
        }

        if ($request->get('name')) {
            $file->name = $request->get('name');
        }

        if ($file->save()) {
            flash(trans('messages.ressource_updated_successfully'));

            return redirect()->route('groups.files.index', [$group]);
        } else {
            flash(trans('messages.ressource_not_updated_successfully'));

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
            flash(trans('messages.ressource_deleted_successfully'));

            return redirect()->route('groups.files.index', [$group]);
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
            if ($request->get('tags')) {
                $file->tag($request->get('tags'));
            }

            // update activity timestamp on parent items
            $group->touch();
            \Auth::user()->touch();

            flash(trans('messages.ressource_created_successfully'));

            return redirect()->route('groups.files.index', $group);
        } else {
            flash(trans('messages.ressource_not_created_successfully'));

            return redirect()->back()->withInput();
        }
    }
}
