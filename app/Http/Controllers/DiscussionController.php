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
        $this->middleware('group.member', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }


    public function indexUnRead($id)
    {

      if ($id) {
          $group = Group::findOrFail($id);
          $discussions = $group->discussions()
          ->select('discussions.*')
          ->leftJoin('user_read_discussion', function($join)
          {
            $join->on('user_read_discussion.discussion_id', '=', 'discussions.id')
            ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
            ->on('user_read_discussion.read_at', '>=', 'discussions.created_at');
          })
          ->whereNull('user_read_discussion.id')

          ->orderBy('discussions.updated_at', 'desc')->paginate(10);

          //$discussions->load('comments');

          return view('discussions.index')
          ->with('discussions', $discussions)
          ->with('group', $group)
          ->with('tab', 'discussion');
      }


}


    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index($id)
    {
        /*
        At some point something like that might be usefull to return all the unread topics :

        SELECT COUNT(comments.id) AS count
                      FROM comments
                      INNER JOIN discussions ON comments.commentable_id = 90
                      LEFT JOIN user_read_discussion h ON comments.commentable_id = h.discussion_id AND h.user_id = 11
                      WHERE (comments.created_at > h.read_at OR h.read_at IS NULL)

        */


        if ($id) {
            $group = Group::findOrFail($id);
            $discussions = $group->discussions()->orderBy('updated_at', 'desc')->paginate(50);


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

        // According to https://www.reddit.com/r/laravel/comments/2l2ndq/unread_forum_posts/
        // When a user visits the topic, if they've never done so before,
        // create a new record in the table with the time they've read the topic.

        if (Auth::user())
        {
            $UserReadDiscussion = \App\UserReadDiscussion::firstOrNew(['discussion_id' => $discussion->id, 'user_id' => Auth::user()->id]);
            $UserReadDiscussion->read_at = Carbon::now();
            $UserReadDiscussion->save();
        }

        //$comments = $discussion->comments;
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
