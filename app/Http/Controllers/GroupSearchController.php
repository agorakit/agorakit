<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Group;


class GroupSearchController extends Controller
{
  public function index(Request $request)
  {
    if ($request->has('search'))
    {
      $results = Group::publicGroups()->search($request->get('search'))->get();
    }
    else {
      $results = null;
    }

    return $results;
  }

}
