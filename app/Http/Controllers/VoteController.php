<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;


class VoteController extends Controller {


  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('group.member');
  }

  public function up($group_id, $discussion_id, $comment_id)
  {
    $vote = \App\Vote::firstOrNew(['comment_id' => $comment_id, 'user_id' => auth()->user()->id]);


    // if the vote was not already 1, we can increment the total in the comment table
    if ($vote->vote == -1)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote = $comment->vote + 2;
      $comment->save();
    }

    if ($vote->vote == 0)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote++;
      $comment->save();
    }


    $vote->vote = 1;
    $vote->save();

    return redirect()->back();

  }

  public function down($group_id, $discussion_id, $comment_id)
  {
    $vote = \App\Vote::firstOrNew(['comment_id' => $comment_id, 'user_id' => auth()->user()->id]);


    if ($vote->vote == 1)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote = $comment->vote - 2;
      $comment->save();
    }

    if ($vote->vote == 0)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote = $comment->vote - 1;
      $comment->save();
    }

    

    $vote->vote = -1;
    $vote->save();

    return redirect()->back();
  }

  public function cancel($group_id, $discussion_id, $comment_id)
  {
    $vote = \App\Vote::firstOrNew(['comment_id' => $comment_id, 'user_id' => auth()->user()->id]);


    // if the vote was 1, we can decrement the total in the comment table
    if ($vote->vote == 1)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote--;
      $comment->save();
    }

    // if the vote was -1, we can increment the total in the comment table
    if ($vote->vote == -1)
    {
      $comment =  \App\Comment::findOrFail($comment_id);
      $comment->vote++;
      $comment->save();
    }

    $vote->vote = 0;
    $vote->save();

    return redirect()->back();
  }



  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {

  }

  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create()
  {

  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store()
  {

  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function show($id)
  {

  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {

  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function update($id)
  {

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function destroy($id)
  {

  }

}

?>
