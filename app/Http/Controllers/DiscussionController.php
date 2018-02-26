<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Group;
use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('member', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('public', ['only' => ['index', 'show', 'history']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request, Group $group)
    {
        if (\Auth::check())
        {
            $discussions = $group->discussions()->has('user')->with('userReadDiscussion', 'user')->orderBy('updated_at', 'desc')->paginate(50);
        }
        else
        { // don't load the unread relation, since we don't know who to look for.
            $discussions = $group->discussions()->has('user')->with('user')->orderBy('updated_at', 'desc')->paginate(50);
        }

        return view('discussions.index')
        ->with('discussions', $discussions)
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
        return view('discussions.create')
        ->with('group', $group)
        ->with('all_tags', \App\Discussion::allTags())
        ->with('tab', 'discussion');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    public function store(Request $request, Group $group)
    {
        // if no group is in the route, it means user choose the group using the dropdown
        if (!$group->exists)
        {
            $group = \App\Group::findOrFail($request->get('group'));
        }

        $this->authorize('creatediscussion', $group);


        $discussion = new Discussion();
        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');

        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate(Auth::user());

        if (!$group->discussions()->save($discussion)) {
            // Oops.
            return redirect()->route('groups.discussions.create', $group->id)
            ->withErrors($discussion->getErrors())
            ->withInput();
        }

        // update activity timestamp on parent items
        $group->touch();
        \Auth::user()->touch();

        if ($request->get('tags'))
        {
            $discussion->tag($request->get('tags'));
        }

        flash(trans('messages.ressource_created_successfully'))->success();

        return redirect()->route('groups.discussions.show', [$group->id, $discussion->id]);
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
        // if user is logged in, we update the read count for this discussion.
        // just before that, we save the number of already read comments in $read_comments to be used in the view to scroll to the first unread comments
        if (Auth::check()) {
            $UserReadDiscussion = \App\UserReadDiscussion::firstOrNew(['discussion_id' => $discussion->id, 'user_id' => Auth::user()->id]);

            $read_comments = $UserReadDiscussion->read_comments;
            $UserReadDiscussion->read_comments = $discussion->total_comments;
            $UserReadDiscussion->read_at = Carbon::now();
            $UserReadDiscussion->save();
        } else {
            $read_comments = 0;
        }

        return view('discussions.show')
        ->with('discussion', $discussion)
        ->with('read_comments', $read_comments)
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
        return view('discussions.edit')
        ->with('discussion', $discussion)
        ->with('group', $group)
        ->with('all_tags', \App\Discussion::allTags())
        ->with('model_tags', $discussion->tags)
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
        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');
        //$discussion->user()->associate(Auth::user()); // we use revisionable to manage who changed what, so we keep the original author
        $discussion->save();

        if ($request->get('tags'))
        {
            $discussion->retag($request->get('tags'));
        }

        flash(trans('messages.ressource_updated_successfully'))->success();

        return redirect()->route('groups.discussions.show', [$discussion->group->id, $discussion->id]);
    }

    public function destroyConfirm(Request $request, Group $group, Discussion $discussion)
    {
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
        if (Gate::allows('delete', $discussion)) {
            $discussion->delete();
            flash(trans('messages.ressource_deleted_successfully'))->success();

            return redirect()->route('groups.discussions.index', [$group]);
        } else {
            abort(403);
        }
    }

    /**
    * Show the revision history of the discussion.
    */
    public function history(Group $group, Discussion $discussion)
    {
        return view('discussions.history')
        ->with('group', $group)
        ->with('discussion', $discussion)
        ->with('tab', 'discussion');
    }
}
