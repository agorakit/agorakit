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
    $this->middleware('cache', ['only' => ['index', 'show']]);
  }


  /**
  * Generates a list of unread discussions.
  */
  public function indexUnRead()
  {

    $discussions = QueryHelper::getUnreadDiscussions();

    foreach ($discussions as $discussion)
    {
      $discussion->updated_at_human = Carbon::parse($discussion->updated_at)->diffForHumans();
    }

    return view('discussions.unread')
    ->with('discussions', $discussions);
  }



  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index($id)
  {

    if ($id) {
      $group = Group::findOrFail($id);


      if (\Auth::check())
      {
        $discussions = $group->discussions()->with('userReadDiscussion', 'user')->orderBy('updated_at', 'desc')->paginate(50);
      }
      else // don't load the unread relation, since we don't know who to look for.
      {
        $discussions = $group->discussions()->with('user')->orderBy('updated_at', 'desc')->paginate(50);
      }

      //dd($discussions->first()->unReadCount());

      return view('discussions.index')
      ->with('discussions', $discussions)
      ->with('group', $group)
      ->with('tab', 'discussion');
    }
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create(Request $request, $group_id)
  {
    $group = Group::findOrFail($group_id);

    return view('discussions.create')
    ->with('group', $group)
    ->with('tab', 'discussion');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store(Request $request, $group_id)
  {
    $discussion = new Discussion();
    $discussion->name = $request->input('name');
    $discussion->body = $request->input('body');


    $discussion->user()->associate(Auth::user());


    $group = Group::findOrFail($group_id);
    $group->discussions()->save($discussion);

    return redirect()->action('DiscussionController@index', [$group->id]);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function show($group_id, $discussion_id)
  {

    $discussion = Discussion::findOrFail($discussion_id);
    $group = Group::findOrFail($group_id);


    // if user is logged in, we update the read count for this discussion.
    if (Auth::check())
    {
      $UserReadDiscussion = \App\UserReadDiscussion::firstOrNew(['discussion_id' => $discussion->id, 'user_id' => Auth::user()->id]);
      $UserReadDiscussion->read_comments = $discussion->total_comments;
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
  public function edit(Request $request, $group_id, $discussion_id)
  {
    $discussion = Discussion::findOrFail($discussion_id);
    $group = $discussion->group;

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
  public function update(Request $request, $group_id, $discussion_id)
  {
    $discussion = Discussion::findOrFail($discussion_id);
    $discussion->name = $request->input('name');
    $discussion->body = $request->input('body');

    $discussion->user()->associate(Auth::user());

    $discussion->save();

    return redirect()->action('DiscussionController@show', [$discussion->group->id]);
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
}
