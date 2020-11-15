<?php

namespace App\Http\Controllers;

use App\File;
use App\Group;
use Auth;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Group $group)
    {
        $this->authorize('view-files', $group);

        // Validate query string, I feel it's better for sql injection prevention  :-)
        if (!in_array($request->get('dir'), ['asc', 'desc', null])) {
            abort(404, 'invalid sort order');
        }

        if (!in_array($request->get('sort'), ['created_at', 'name', 'filesize', null])) {
            abort(404, 'invalid sort type');
        }

        if ($group->tagsAreLimited()) {
            $tags = $group->allowedTags();
        } else {
            $tags = $group->tagsInFiles();
        }

        // Query depending of the request
        // filter by tags and sort order
        $tag = $request->get('tag');

        $files = $group->files()
        ->where('item_type', '<>', \App\File::FOLDER)
        ->with('user', 'group', 'tags')
        ->orderBy('status', 'desc')
        ->orderBy($request->get('sort', 'created_at'), $request->get('dir', 'desc'))
        ->when($tag, function ($query) use ($tag) {
            return $query->withAnyTags($tag);
        })
        ->paginate(20);

        return view('files.index')
        ->with('title', $group->name.' - '.trans('messages.files'))
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
        $this->authorize('view', $file);

        return view('files.show')
        ->with('title', $group->name.' - '.$file->name)
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
        $this->authorize('create-file', $group);

        $file = new File;
        $file->group()->associate($group);

        return view('files.create')
        ->with('allowedTags', $file->getAllowedTags())
        ->with('newTagsAllowed', $file->areNewTagsAllowed())
        ->with('group', $group)
        ->with('tab', 'files');
    }

    public function createLink(Request $request, Group $group)
    {
        $this->authorize('create-link', $group);

        $file = new File;
        $file->group()->associate($group);


        return view('files.createlink')
        ->with('allowedTags', $file->getAllowedTags())
        ->with('newTagsAllowed', $file->areNewTagsAllowed())
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
        $this->authorize('create-file', $group);

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

                    // Add file to disk
                    $file->addToStorage($uploaded_file);

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
            } else {
                abort(400, trans('messages.no_file_selected'));
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
        $this->authorize('update', $file);

       

        return view('files.edit')
        ->with('file', $file)
        ->with('allowedTags', $file->getAllowedTags())
        ->with('newTagsAllowed', $file->areNewTagsAllowed())
        ->with('selectedTags', $file->getSelectedTags())
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
        $this->authorize('update', $file);

        if ($request->get('tags')) {
            $file->retag($request->get('tags'));
        } else {
            $file->detag();
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
        $this->authorize('delete', $file);

        return view('files.delete')
        ->with('group', $group)
        ->with('file', $file)
        ->with('tab', 'file');
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
        $this->authorize('delete', $file);

        $file->delete();
        flash(trans('messages.ressource_deleted_successfully'));

        return redirect()->route('groups.files.index', [$group]);
    }

    /**
     * Store the folder in the file DB.
     *
     * @return Response
     */
    public function storeLink(Request $request, Group $group)
    {
        $this->authorize('create-file', $group);

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

    public function pin(Group $group, File $file)
    {
        $this->authorize('pin', $file);
        $file->togglePin();
        $file->timestamps = false;
        $file->save();
        flash(trans('messages.ressource_updated_successfully'));
        return redirect()->back();
    }

    public function archive(Group $group, File $file)
    {
        $this->authorize('archive', $file);
        $file->toggleArchive();
        $file->timestamps = false;
        $file->save();
        flash(trans('messages.ressource_updated_successfully'));
        return redirect()->back();
    }
}
