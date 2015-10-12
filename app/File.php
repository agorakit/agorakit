<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Storage;
use Response;

class File extends Model
{
  use ValidatingTrait;

  protected $rules = [
    'path' => 'required',
    'user_id' => 'required',
  ];

  protected $table = 'files';
  public $timestamps = true;

  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // TODO performance ?
  protected $with = ['user'];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function group()
  {
    return $this->belongsTo('App\Group');
  }

  public function setFileContent($file_content, $filename, $extension, $mime)
  {
    // path would be in the form storage/app/group/{group_id}/{file_id}.jpg for a jpeg file
    $path = 'groups/'.$this->group_id.'/'.$this->id.'.'.$extension;

    $this->path = $path;
    $this->original_filename = $filename; // we never know it might be useful
    $this->original_extension = $extension;  // we never know it might be useful
    $this->mime = $mime;

    return (Storage::put($path,  $file_content));
  }

  public function getFileContent()
  {
    $file = Storage::disk('local')->get($this->path);

    return (new Response($file, 200, ['Content-Type', $entry->mime]));
  }
}
