<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\File;
use App\Group;
use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class GroupDiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('member', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Group $group)
    {
        $this->authorize('view-discussions', $group);

        $discussion = new Discussion;
        $discussion->group()->associate($group);

        // for the tag filter frop down
        $tags = $discussion->getTagsInUse();
        $tag = $request->get('tag');

        if (Auth::check()) {
            $discussions =
            $group->discussions()
            ->has('user')
            ->with('userReadDiscussion', 'group', 'user', 'tags', 'comments')
            ->withCount('comments')
            ->orderBy('status', 'desc')
            ->orderBy('updated_at', 'desc')
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            });
           
        } else { // don't load the unread relation, since we don't know who to look for.
            $discussions = $group->discussions()->has('user')->with('user', 'group', 'tags')->withCount('comments')->orderBy('updated_at', 'desc');
        }

        if ($request->has('search')) {
            $discussions = $discussions->search($request->get('search'));
        }

        $discussions = $discussions->paginate(50);

        return view('discussions.index')
        ->with('title', $group->name.' - '.trans('messages.discussions'))
        ->with('discussions', $discussions)
        ->with('tags', $tags)
        ->with('group', $group)
        ->with('tab', 'discussion');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Group $group)
    {
        if ($group->exists) {
            $this->authorize('create-discussion', $group);
        }

        $discussion = new Discussion;
        $discussion->group()->associate($group);

        $title = trans('group.create_group_discussion');

        return view('discussions.create')
        ->with('group', $group)
        ->with('allowedTags', $discussion->getTagsInUse())
        ->with('newTagsAllowed', $discussion->areNewTagsAllowed())
        ->with('tab', 'discussion')
        ->with('title', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Group $group)
    {

        // if no group is in the route, it means user chose the group using the dropdown
        if (!$group->exists) {
            $group = \App\Group::find($request->get('group'));
            //if group is null, redirect to the discussion create page with error messages, saying
            //that you must select a group
            if (is_null($group)) {
                return redirect()->back()->withErrors(trans('You must select a group'));
            }
        }

        $this->authorize('create-discussion', $group);

        $discussion = new Discussion();
        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');
        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate(Auth::user());

        if (!$group->discussions()->save($discussion)) {
            // Oops.
            return redirect()->route('groups.discussions.create', $group)
            ->withErrors($discussion->getErrors())
            ->withInput();
        }

        // handle attached file to comment
        if ($request->hasFile('file')) {
            // create a file instance
            $file = new File();
            $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

            // add group, user
            $file->group()->associate($group);
            $file->user()->associate(Auth::user());

            // store the file itself on disk
            $file->addToStorage($request->file('file'));

            // add an f:xx to the comment so it is shown on display
            $discussion->body = $discussion->body.'<p>f:'.$file->id.'</p>';
            $discussion->save();
        }

        // update activity timestamp on parent items
        $group->touch();
        Auth::user()->touch();

        if ($request->get('tags')) {
            $discussion->tag($request->get('tags'));
        }

        flash(trans('messages.ressource_created_successfully'));

        return redirect()->route('groups.discussions.show', [$group, $discussion]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Group $group, Discussion $discussion)
    {
        $this->authorize('view', $discussion);

        // if user is logged in, we update the read count for this discussion.
        // just before that, we save the number of already read comments in $read_comments 
        // to be used in the view to scroll to the first unread comments
        if (Auth::check()) {
            $unread_count = $discussion->unReadCount();
            $discussion->markAsRead();
        }
        else {
            $unread_count = 0;
        }

        $read_count = $discussion->comments->count() - $unread_count;
        $total_count = $discussion->comments->count();

        /*
        foreach ($discussion->comments as $comment_key => $comment)
        {
            if ($comment_key == $read_count) {
                $comment->isFirstUnread = true;
            }
        }
        
       // {{$comment_key}} / {{$read_count}} / {{$total_count}}
       */

        return view('discussions.show')
        ->with('title', $group->name.' - '.$discussion->name)
        ->with('discussion', $discussion)
        ->with('unread_count', $unread_count)
        ->with('read_count', $read_count)
        ->with('total_count', $total_count)
        ->with('group', $group)
        ->with('tab', 'discussion');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        return view('discussions.edit')
        ->with('discussion', $discussion)
        ->with('group', $group)
        ->with('allowedTags', $discussion->getTagsInUse())
        ->with('newTagsAllowed', $discussion->areNewTagsAllowed())
        ->with('selectedTags', $discussion->getSelectedTags())
        ->with('tab', 'discussion');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');
        //$discussion->user()->associate(Auth::user()); // we use revisionable to manage who changed what, so we keep the original author

        // handle attached file to comment
        if ($request->hasFile('file')) {
            // create a file instance
            $file = new File();
            $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

            // add group, user
            $file->group()->associate($group);
            $file->user()->associate(Auth::user());

            // store the file itself on disk
            $file->addToStorage($request->file('file'));

            // add an f:xx to the comment so it is shown on display
            $discussion->body = $discussion->body.'<p>f:'.$file->id.'</p>';
        }

        $discussion->save();

        if ($request->get('tags')) {
            $discussion->retag($request->get('tags'));
        } else {
            $discussion->detag();
        }

        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
    }

    public function destroyConfirm(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('delete', $discussion);

        if (Gate::allows('delete', $discussion)) {
            return view('discussions.delete')
            ->with('group', $group)
            ->with('discussion', $discussion)
            ->with('tab', 'discussion');
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
    public function destroy(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('view', $discussion);
        $discussion->delete();
        flash(trans('messages.ressource_deleted_successfully'));

        return redirect()->route('groups.discussions.index', [$group]);
    }

    /**
     * Show the revision history of the discussion.
     */
    public function history(Group $group, Discussion $discussion)
    {
        $this->authorize('history', $discussion);

        return view('discussions.history')
        ->with('group', $group)
        ->with('discussion', $discussion)
        ->with('tab', 'discussion');
    }

    public function pin(Group $group, Discussion $discussion)
    {
        $this->authorize('pin', $discussion);
        $discussion->togglePin();
        $discussion->timestamps = false;
        $discussion->save();
        //flash(trans('messages.ressource_updated_successfully'));
        return redirect()->back();
    }

    public function archive(Group $group, Discussion $discussion)
    {
        $this->authorize('archive', $discussion);
        $discussion->toggleArchive();
        $discussion->timestamps = false;
        $discussion->save();
        //flash(trans('messages.ressource_updated_successfully'));
        return redirect()->back();
    }
}
