<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
  public function __construct()
  {
    $this->middleware('preferences');
  }


  /**
  * Generates a global list of discussions with unread count (independant of groups).
  */
  public function index(Request $request)
  {

    $tag = $request->get('tag');

    if (Auth::check()) {
      // All the groups of a user : Auth::user()->groups()->pluck('groups.id')
      // All the public groups : \App\Group::publicgroups()

      // A merge of the two :

      //$groups = \App\Group::publicgroups()
      //->get()
      //->pluck('id')
      //->merge(Auth::user()->groups()->pluck('groups.id'));

      if (Auth::user()->getPreference('show') == 'all') {
        $groups = \App\Group::publicgroups()
        ->get()
        ->pluck('id')
        ->merge(Auth::user()->groups()->pluck('groups.id'));
      } else {
        $groups = Auth::user()->groups()->pluck('groups.id');
      }
      $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user')
      ->whereIn('group_id', $groups)
      ->when($tag, function ($query) use ($tag) {
        return $query->withAnyTags($tag);
      })
      ->orderBy('updated_at', 'desc')->paginate(25);

    } else {
      $discussions = \App\Discussion::with('group', 'user')
      ->whereIn('group_id', \App\Group::publicgroups()->get()->pluck('id'))
      ->when($tag, function ($query) use ($tag) {
        return $query->withAnyTags($tag);
      })
      ->orderBy('updated_at', 'desc')->paginate(25);
    }



    $tags = \App\Discussion::allTags();
    natcasesort($tags);




    return view('dashboard.discussions')
    ->with('title', trans('messages.discussions'))
    ->with('tab', 'discussions')
    ->with('discussions', $discussions)
    ->with('tags', $tags);
  }

}
