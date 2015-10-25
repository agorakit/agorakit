<?php namespace App\Http\Controllers;

class UserController extends Controller {


  public function __construct()
  {
    $this->middleware('cacheforanonymous', ['only' => ['index', 'show']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($group_id)
  {
        $group = \App\Group::with('users')->findOrFail($group_id);
        $users = $group->users()->orderBy('updated_at', 'desc')->paginate(10);

        return view('users.index')
    ->with('users', $users)
    ->with('group', $group)
    ->with('tab', 'users');
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
    return 'not yet'; // TODO
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
