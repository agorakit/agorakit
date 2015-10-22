<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('group.member', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function unRead()
    {

    /****** Working solutions ******/
    // get the count of unraad comments for curentylyloggged in user, irrelative of groups he is part of
    $comments = \App\Comment::select('comments.*')->leftJoin('user_read_discussion', function ($join) {
      $join->on('user_read_discussion.discussion_id', '=', 'comments.discussion_id')
      ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
      ->on('user_read_discussion.read_at', '>=', 'comments.created_at');
    })
    ->whereNull('user_read_discussion.id')
    ->orderBy('comments.created_at', 'desc')
    ->take(25)
    ->get();

    //$comments->load('user');

    //dd($comments);
      return view('comments.index')->with('comments', $comments);

    // number of comment sin a specific discussion $id (here 68)
    /*
    $comments = \App\Comment::leftJoin('user_read_discussion', function($join)
    {
      $join->on('user_read_discussion.discussion_id', '=', 'comments.discussion_id')
      ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
      ->on('user_read_discussion.read_at', '>=', 'comments.created_at');
    })
    ->whereNull('user_read_discussion.id')
    ->where('comments.discussion_id', '=', 68);
    */

    // number of comment sin a specific group
/*
    $comments = \App\Comment::leftJoin('user_read_discussion', function($join)
    {
      $join->on('user_read_discussion.discussion_id', '=', 'comments.discussion_id')
      ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
      ->on('user_read_discussion.read_at', '>=', 'comments.created_at');
    })
    ->whereNull('user_read_discussion.id')
    ->groupBy('comments.discussion_id');
*/

    //return ($comments->count());
    //dd($comments->get());
    return view('comments.index')->with('comments', $comments);

    /*
    $comments = \App\Comment::leftJoin('user_read_discussion', 'user_read_discussion.comment_id', '=', 'comments.id')
    ->on('user_read_discussion', 'user_read_discussion.user_id', '=', \Auth::user()->id)
    ->on('user_read_discussion', 'user_read_discussion.read_at', '>=', 'comments.created_at')
    ->whereNull('user_read_discussion.id');


    SELECT comments.*
    FROM comments
    LEFT JOIN user_read_discussion ON  user_read_discussion.comment_id = comments.id
    AND user_read_discussion.user_id = 42
    AND user_read_discussion.read_at >= comments.created_at
    WHERE user_read_discussion.id IS NULL
    AND comments.created_at > '2010-10-20 08:50:00'

    SELECT comments . *
    FROM comments
    LEFT JOIN user_read_discussion ON user_read_discussion.discussion_id = comments.discussion_id
    AND user_read_discussion.user_id =11
    AND user_read_discussion.read_at >= comments.created_at
    WHERE user_read_discussion.id IS NULL
    AND comments.created_at > '2015-10-17 13:20:26'
    LIMIT 0 , 30


    select * from `comments`
    left join `user_read_discussion` on `user_read_discussion.discussion_id` = `comments.discussion_id`
    and `user_read_discussion.user_id` = 11
    and `user_read_discussion.read_at` >= `comments.created_at`
    where `comments.deleted_at` is null
    and `user_read_discussion.id` is null'
    */
    }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($type, $id)
  {
      if ($type == 'discussion') {
          $discussion = \App\Discussion::findOrFail($id);
          $group = $discussion->group;

          return view('comments.create')
      ->with('discussion', $discussion)
      ->with('group', $group)
      ->with('tab', 'discussion');
      } else {
          abort(401, 'Only discussions can be commented for now');
      }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $type, $id)
  {
      if ($type == 'discussion') {
          $comment = new \App\Comment();
          $comment->body = $request->input('body');

          $comment->user()->associate(\Auth::user());

          $discussion = \App\Discussion::findOrFail($id);
          $discussion->comments()->save($comment);

          $discussion->total_comments ++;
          $discussion->save();


          $group = $discussion->group;

          return redirect()->action('DiscussionController@show', [$group->id, $discussion->id]);
      } else {
          abort(401, 'Only discussions can be commented for now');
      }
  }

    public function reply(Request $request, $group_id, $discussion_id)
    {
        $comment = new \App\Comment();
        $comment->body = $request->input('body');

        $comment->user()->associate(\Auth::user());

        $discussion = \App\Discussion::findOrFail($discussion_id);

        $discussion->reply($comment);

        $group = $discussion->group;

        return redirect()->action('DiscussionController@show', [$group->id, $discussion->id]);
    }

  /**
   * Display the specified resource.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int                      $id
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
