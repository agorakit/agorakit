<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Carbon\Carbon;

class Action extends Model
{


  use ValidatingTrait;

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

  use SoftDeletes;

  protected $dates = ['deleted_at', 'start', 'stop'];

  /**
   * Returns a summary of this item of $length
   */
  public function summary($length = 200)
  {
    return str_limit(strip_tags($this->body), $length);

  }

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
}
