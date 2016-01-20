<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Discussion;
use App\Group;
use Carbon\Carbon;
use DB;
use App\Helpers\QueryHelper;

class DiscussionController extends Controller
{


  public function __construct()
  {
    $this->middleware('auth', ['only' => ['indexUnRead']]);
    $this->middleware('member', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('cache', ['only' => ['index', 'show']]);
  }


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index(Group $group)
  {
    if (\Auth::check())
    {
      $discussions = $group->discussions()->with('userReadDiscussion', 'user')->orderBy('updated_at', 'desc')->paginate(50);
    }
    else // don't load the unread relation, since we don't know who to look for.
    {
      $discussions = $group->discussions()->with('user')->orderBy('updated_at', 'desc')->paginate(50);
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
    ->with('tab', 'discussion');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store(Request $request, Group $group)
  {
    $discussion = new Discussion();
    $discussion->name = $request->input('name');
    $discussion->body = clean($request->input('body'));

    $discussion->total_comments = 1; // the discussion itself is already a comment
    $discussion->user()->associate(Auth::user());

    if ( !$group->discussions()->save($discussion) )
    {
      // Oops.
      return redirect()->action('DiscussionController@create', $group_id)
      ->withErrors($discussion->getErrors())
      ->withInput();
    }

    $request->session()->flash('message', trans('messages.ressource_created_successfully'));
    return redirect()->action('DiscussionController@show', [$group->id, $discussion->id]);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function show(Group $group, Discussion $discussion)
  {
    // if user is logged in, we update the read count for this discussion.
    if (Auth::check())
    {
      $UserReadDiscussion = \App\UserReadDiscussion::firstOrNew(['discussion_id' => $discussion->id, 'user_id' => Auth::user()->id]);
      $UserReadDiscussion->read_comments = $discussion->total_comments;
      $UserReadDiscussion->read_at = Carbon::now();
      $UserReadDiscussion->save();
    }

    return view('discussions.show')
    ->with('discussion', $discussion)
    ->with('group', $group)
    ->with('tab', 'discussion');
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function edit(Request $request, Group $group, Discussion $discussion)
  {
    $tags = Discussion::existingTags();
    return view('discussions.edit')
    ->with('discussion', $discussion)
    ->with('group', $group)
    ->with('tab', 'discussion');
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function update(Request $request, Group $group, Discussion $discussion)
  {
    $discussion->name = $request->input('name');
    $discussion->body = clean($request->input('body'));
    $discussion->user()->associate(Auth::user());
    $discussion->save();

    $request->session()->flash('message', trans('messages.ressource_updated_successfully'));
    return redirect()->action('DiscussionController@show', [$discussion->group->id, $discussion->id]);
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function destroy($id)
  {
  }


  /**
  * Show the revision history of the discussion
  */
  public function history(Group $group, Discussion $discussion)
  {
    return view('discussions.history')
    ->with('group', $group)
    ->with('discussion', $discussion)
    ->with('tab', 'discussion');
  }

}
