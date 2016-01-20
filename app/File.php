<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Conner\Tagging\Taggable;
use Storage;
use Response;

class File extends Model
{
  use ValidatingTrait;
  use SoftDeletes;
  use Taggable;

  protected $rules = [
    'path' => 'required',
    'user_id' => 'required|exists:users,id',
    'group_id' => 'required|exists:groups,id',
  ];


  protected $table = 'files';
  public $timestamps = true;
  protected $dates = ['deleted_at'];
  protected $casts = [ 'user_id' => 'integer' ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function group()
  {
    return $this->belongsTo('App\Group');
  }

}
