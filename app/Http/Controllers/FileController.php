<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FileController extends Controller
{
  public function __construct()
  {
    $this->middleware('verified');
    $this->middleware('preferences');
  }


  /**
  * Show all the files independant of groups.
  */
  public function index(Request $request)
  {
    $tags = \App\File::allTags();

    natcasesort($tags);


    if (Auth::check()) {
      $groups = \App\Group::publicgroups()
      ->get()
      ->pluck('id')
      ->merge(Auth::user()->groups()->pluck('groups.id'));

      if ($request->get('tag')) {
        $files = \App\File::with('group', 'user')
        ->withAnyTags($request->get('tag'))
        ->where('item_type', '<>', \App\File::FOLDER)
        ->whereIn('group_id', $groups)
        ->orderBy('created_at', 'desc')->paginate(25);
      } else {
        $files = \App\File::with('group', 'user')
        ->where('item_type', '<>', \App\File::FOLDER)
        ->whereIn('group_id', $groups)
        ->orderBy('created_at', 'desc')->paginate(25);
      }
    } else {
      $files = \App\File::with('group', 'user')
      ->where('item_type', '<>', \App\File::FOLDER)
      ->whereIn('group_id', \App\Group::publicgroups()->get()->pluck('id'))
      ->orderBy('updated_at', 'desc')->paginate(25);
    }


    return view('dashboard.files')
    ->with('tags', $tags)
    ->with('tab', 'files')
    ->with('files', $files);
  }




}
