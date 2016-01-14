<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use Gate;

class UserController extends Controller {


  public function __construct()
  {
    $this->middleware('cache', ['only' => ['index', 'show']]);
    $this->middleware('verified', ['only' => ['contact', 'mail']]);
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
  * Show contact form for the user
  */
  public function contact($user_id)
  {
    $user = \App\User::findOrFail($user_id);
    return view('users.contact')
    ->with('user', $user);
  }

  /**
  * Mails the user
  */
  public function mail($user_id, Request $request)
  {
    $to_user = \App\User::findOrFail($user_id);
    $from_user = Auth::user();


    $requestsPerHour = 5;

    // Rate limit by IP address
    $key = 'contact_user_' . $to_user->id . '_from_user_' . $from_user->id;

    // Add if doesn't exist
    // Remember for 1 hour
    \Cache::add($key, 0, 60);

    // Add to count
    $count = \Cache::increment($key);

    if( $count > $requestsPerHour )
    {
      $request->session()->flash('message', trans('messages.message_not_sent_too_many_per_hour'));
    }
    else
    {
      if ($request->has('body'))
      {
        $body = $request->input('body');

        Mail::send('emails.contact', ['to_user' => $to_user, 'from_user' => $from_user, 'body' => $body ], function ($message) use ($to_user, $from_user) {
          $message->from($from_user->email, $from_user->name)
          ->to($to_user->email, $to_user->name)
          ->subject('[' . env('APP_NAME') . '] ' . trans('messages.a_message_for_you'));
        });

        $request->session()->flash('message', trans('messages.message_sent'));
      }
    }
    return redirect()->action('UserController@show', $to_user->id);
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
    $user = \App\User::findOrFail($id);
    return view('users.show')->with('user', $user);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {
    $user = \App\User::findOrFail($id);
    if (Gate::allows('update', $user))
    {
      return view('users.edit')
      ->with('user', $user);
    }
    else
    {
      abort(403);
    }
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
