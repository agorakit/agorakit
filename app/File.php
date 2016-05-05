<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Storage;
use Response;

use DraperStudio\Taggable\Contracts\Taggable;
use DraperStudio\Taggable\Traits\Taggable as TaggableTrait;

class File extends Model implements Taggable
{
  use ValidatingTrait;
  use SoftDeletes;
  use TaggableTrait;

  protected $onlyUseExistingTags = false;


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


  public function link()
  {
      return action('FileController@show', [$this->group, $this]);
  }

}
