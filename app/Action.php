<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Carbon\Carbon;

class Action extends Model
{


  use ValidatingTrait;
  use RevisionableTrait;
  use SoftDeletes;

  protected $fillable = ['id']; // neede for actions import

  protected $rules = [
    'name' => 'required|min:5',
    'user_id' => 'required|exists:users,id',
    'group_id' => 'required|exists:groups,id',
    'start' => 'required',
    'stop' => 'required',
  ];


  protected $table = 'actions';
  public $timestamps = true;
  protected $dates = ['deleted_at', 'start', 'stop'];
  protected $casts = [ 'user_id' => 'integer' ];



  public function group()
  {
    return $this->belongsTo('\App\Group');
  }

  public function user()
  {
    return $this->belongsTo('\App\User');
  }

  public function votes()
  {
    return $this->morphMany('Vote', 'votable');
  }

  public function link()
  {
      return action('ActionController@show', [$this->group, $this]);
  }

}
