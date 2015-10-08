<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Discussion;
use App\Group;

class DiscussionController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($id)
  {
    if ($id)
    {
    $group = Group::findOrFail($id);
    $discussions = $group->discussions()->orderBy('updated_at', 'desc')->paginate(10);



    return view ('discussions.index')
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
      return view ('discussions.create')
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

        $discussion = new Discussion;
        $discussion->name = $request->input('name');
        $discussion->body = $request->input('body');

        if (Auth::check())
        {
          $discussion->user()->associate(Auth::user());
        }
        else {
          abort(401, 'user not logged in TODO');
        }

        $group = Group::findOrFail($group_id);
        $group->discussions()->save($discussion);


        return redirect()->action('DiscussionController@index', [$group->id]);


  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($group_id, $discussion_id)
  {


    $discussion = Discussion::findOrFail($discussion_id);
    $group = Group::findOrFail($group_id);
    //$group = $discussion->group()->first();
    $author = $discussion->user;
    $comments = $discussion->comments;
    return view ('discussions.show')
      ->with('discussion', $discussion)
      ->with('group', $group)
      ->with('author', $author)
      //->with('comments', $comments)
      ->with('tab', 'discussion');
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
