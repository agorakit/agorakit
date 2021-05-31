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


        $file = new File;
        $file->group()->associate($group);


        // for the tag filter frop down
        $tags = $file->getTagsInUse();
        $tag = $request->get('tag');

        $parent = $request->get('parent');



        if ($parent) {
            // load the current parent and it's parents in a single "parents" collection
            $file = File::findOrFail($parent);
            $parents = $file->parents(true);
            $breadcrumb = $parents->reverse();
        } else {
            $parents = false;
            $breadcrumb = false;
        }


        $folders = $group->files()
            ->with('user', 'group', 'tags')
            ->where('item_type', File::FOLDER)
            ->orderBy('status', 'desc')
            ->orderBy('name', 'asc')
            ->when($parent, function ($query) use ($parent) {
                return $query->where('parent_id', $parent);
            })
            ->when(!$parent, function ($query) {
                return $query->whereNull('parent_id');
            })
            ->get();

        $files = $group->files()
            ->with('user', 'group', 'tags')
            ->where('item_type', '<>', File::FOLDER)
            ->orderBy('status', 'desc')
            ->orderBy($request->get('sort', 'created_at'), $request->get('dir', 'desc'))
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->when($parent, function ($query) use ($parent) {
                return $query->where('parent_id', $parent);
            })
            ->when(!$parent, function ($query) {
                return $query->whereNull('parent_id');
            })
            ->paginate(20);


        return view('files.index')
            ->with('title', $group->name . ' - ' . trans('messages.files'))
            ->with('folders', $folders)
            ->with('files', $files)
            ->with('parent', $parent)
            ->with('parents', $parents)
            ->with('breadcrumb', $breadcrumb)
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

        if ($file->isFolder()) {
            return redirect()->route('groups.files.index', ['group' => $group, 'parent' => $file]);
        }

        if ($file->parents()) {
            // load the current parent and it's parents in a single "parents" collection
            $parents = $file->parents();
            $breadcrumb = $parents->reverse();
        } else {
            $parents = false;
            $breadcrumb = false;
        }


        return view('files.show')
            ->with('title', $group->name . ' - ' . $file->name)
            ->with('file', $file)
            ->with('breadcrumb', $breadcrumb)
            ->with('group', $group)
            ->with('tab', 'files');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Group $group, File $parent = null)
    {
        $this->authorize('create-file', $group);

        $file = new File;
        $file->group()->associate($group);



        return view('files.create')
            ->with('allowedTags', $file->getTagsInUse())
            ->with('newTagsAllowed', $file->areNewTagsAllowed())
            ->with('group', $group)
            ->with('parent', $parent)
            ->with('tab', 'files');
    }

    public function createLink(Request $request, Group $group, File $parent = null)
    {
        $this->authorize('create-link', $group);

        $file = new File;
        $file->group()->associate($group);


        return view('files.createlink')
            ->with('allowedTags', $file->getTagsInUse())
            ->with('newTagsAllowed', $file->areNewTagsAllowed())
            ->with('group', $group)
            ->with('parent', $parent)
            ->with('tab', 'files');
    }

    public function createFolder(Request $request, Group $group, File $parent = null)
    {
        $this->authorize('create-folder', $group);

        return view('files.createfolder')
            ->with('group', $group)
            ->with('parent', $parent)
            ->with('tab', 'files');
    }

    /**
     * Store a new file.
     *
     * @return Response
     */
    public function store(Request $request, Group $group, File $parent = null)
    {
        $this->authorize('create-file', $group);

        // handle the case of a summernote upaload (via ajax)
        if ($request->ajax()) {
            if ($request->file('file')) {
                $file = new File();
                $file->forceSave();
                $file->group()->associate($group);
                $file->user()->associate(Auth::user());
                $file->addToStorage($request->file('file'));
                $group->touch();
                \Auth::user()->touch();

                //return response()->json(route('groups.files.preview', [$group, $file]), 200, [], JSON_UNESCAPED_SLASHES);
                return response()->json($file->id);
            }
            return response()->json('no file found in request', 404, [], JSON_UNESCAPED_SLASHES);
        }

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


                    if ($parent) {
                        $file->setParent($parent);
                    }

                    // Add file to disk
                    $file->addToStorage($uploaded_file);

                    // update activity timestamp on parent items
                    $group->touch();
                    \Auth::user()->touch();
                }

                flash(trans('messages.ressource_created_successfully'));



                return redirect()->route('groups.files.index', ['group' => $group, 'parent' => $parent]);
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
     * Store the folder in the file DB.
     *
     * @return Response
     */
    public function storeLink(Request $request, Group $group, File $parent = null)
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

        // set parent
        if ($parent) {
            $file->setParent($parent);
        }

        if ($file->save()) {
            // handle tags
            if ($request->get('tags')) {
                $file->tag($request->get('tags'));
            }

            // update activity timestamp on parent items
            $group->touch();
            \Auth::user()->touch();

            flash(trans('messages.ressource_created_successfully'));
            return redirect()->route('groups.files.index', ['group' => $group, 'parent' => $parent]);
        } else {
            flash(trans('messages.ressource_not_created_successfully'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Store the folder in the file DB.
     *
     * @return Response
     */
    public function storeFolder(Request $request, Group $group, File $parent = null)
    {
        $this->authorize('create-file', $group);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = new File();
        $file->name = $request->get('name');

        $file->item_type = File::FOLDER;
        // add group
        $file->group()->associate($group);

        // add user
        $file->user()->associate(Auth::user());

        // set parent
        if ($parent) {
            $file->setParent($parent);
        }

        if ($file->save()) {
            // update activity timestamp on parent items
            $group->touch();
            \Auth::user()->touch();
            flash(trans('messages.ressource_created_successfully'));
            return redirect()->route('groups.files.index', ['group' => $group, 'parent' => $parent]);
        } else {
            flash(trans('messages.ressource_not_created_successfully'));

            return redirect()->back()->withInput();
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
            ->with('folders', $group->folders)
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
    public function update(Request $request, Group $group, File $file, File $parent = null)
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

        if ($request->has('parent')) {
            // handle null case (aka root)
            if ($request->get('parent') == 'root') {
                $parent = null;
                $file->setParent(null);
            } else {
                $parent = File::find($request->get('parent'));
                $file->setParent($parent);
            }
        }

        if ($request->has('file')) 
        {
            $file->addToStorage($request->file('file'));
        }

        if ($file->save()) {
            flash(trans('messages.ressource_updated_successfully'));

            return redirect()->route('groups.files.index', ['group' => $group, 'parent' => $parent]);
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
