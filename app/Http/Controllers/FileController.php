<?php namespace App\Http\Controllers;

use \App\Group;

use Input;
use Response;
use Storage;
use Request;
use File;
use Auth;


class FileController extends Controller {

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
      $files = $group->files()->orderBy('updated_at', 'desc')->paginate(20);
      return view ('files.index')
      ->with('files', $files)
      ->with('group', $group)
      ->with('tab', 'files');
    }

  }

  /**
  * Returns the file to the user as a download
  */
  public function download($id)
  {
    dd( File::findOrFail($id));
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
  public function store(Request $request, $id)
  {

    // TODO this should be part of the file model logic in a more abstracted way, it does (partially) not belong to a controller.
    // something like $file->addUpload($file_object) etc...



    $uploaded_file = Request::file('file');
    $extension = $uploaded_file->getClientOriginalExtension();
    if (Storage::put('groups/' . $uploaded_file->getFilename().'.'.$extension,  File::get($uploaded_file)))
    {
      $file = new \App\File;
      $file->path = $uploaded_file->getFilename();


      if (Auth::check())
      {
        $file->user()->associate(Auth::user());
      }
      else {
        abort(401, 'user not logged in TODO');
      }

      $group = Group::findOrFail($id);
      $group->files()->save($file);

      return Response::json('success', 200);
    }
    else
    {
      return Response::json('error', 400);
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
