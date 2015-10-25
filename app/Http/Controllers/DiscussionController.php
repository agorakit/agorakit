<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Discussion;
use App\Group;
use Carbon\Carbon;
use DB;

class DiscussionController extends Controller
{


  public function __construct()
  {
    $this->middleware('auth', ['only' => ['indexUnRead']]);
    $this->middleware('group.member', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('cacheforanonymous', ['only' => ['index', 'show']]);
  }



  public function test($id = null)
  {
    $discussions = \App\Discussion::with('userReadDiscussion')->with('user')->findOrFail($id);
    dd($discussions);
  }

  /**
  * Generates a list of unread discussions.
  * If a group id is provided only unread from this group is socket_shutdown
  */
  public function indexUnRead($group_id = null)
  {

    if ($group_id) {
      $group = Group::findOrFail($group_id);
      $discussions = $group->discussions()
      ->select('discussions.*')
      ->leftJoin('user_read_discussion', function($join)
      {
        $join->on('user_read_discussion.discussion_id', '=', 'discussions.id')
        ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
        ->on('user_read_discussion.read_comments', '>=', 'discussions.total_comments');
      })
      ->whereNull('user_read_discussion.id')

      ->orderBy('discussions.updated_at', 'desc')->paginate(10);



      return view('discussions.index')
      ->with('discussions', $discussions)
      ->with('group', $group)
      ->with('tab', 'discussion');
    }
    else
    {

      // this is so far the hardest (and not so ugly anymore, but raw) part :

      $discussions =   \App\Discussion::hydrateRaw('

          SELECT *,
          (
            SELECT user_read_discussion.read_comments
            FROM user_read_discussion
            WHERE user_read_discussion.user_id = :user_id_one
            AND user_read_discussion.discussion_id = discussions.id
          ) as read_comments

          FROM discussions
          WHERE group_id
          in (
            SELECT membership.group_id
            FROM membership
            WHERE membership.user_id = :user_id_two
            and membership.membership > 0
          )


      '
      , ['user_id_one' => Auth::user()->id, 'user_id_two' => Auth::user()->id]);

      //WHERE  discussions.total_comments > read_comments


        //dd ($discussions);

          return view('discussions.general_index')
          ->with('discussions', $discussions);
        }






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
