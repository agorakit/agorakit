<?php

namespace App\Http\Controllers;
use App\Group;
use Auth;
use App\Discussion;
use Illuminate\Http\Request;


class DiscussionController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

  }

  /**
   * Lists all the discussions of this particular group
   */
  public function groupIndex($groupid)
  {
    $discussions = Group::findOrFail($groupid)->discussions();
    return view ('discussions.index')->with('discussions', $discussions);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    if ($request->has('group_id'))
    {
        return view ('discussions.create')->with('group_id', $request->input('group_id'));
    }
    else
    {
        abort(404, 'You need to provide a group_id in the request');
    }


  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    if ($request->has('group_id'))
    {
        $discussion = new Discussion;
        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');


        $group = Group::findOrFail($request->input('group_id'));
        $group->discussions()->save($discussion);


        return redirect('group/' . $group->id);
    }
    else
    {
        abort(404, 'You need to provide a group_id in the request');
    }

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $discussion = Discussion::findOrFail($id);
    return view ('discussions.show')->with('discussion', $discussion);
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
