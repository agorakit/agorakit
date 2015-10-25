<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('group.member', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
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
