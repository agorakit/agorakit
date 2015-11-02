<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Invite extends Model
{
  use ValidatingTrait;

  public $timestamps = true;
  protected $dates = ['deleted_at'];


  protected $rules = [
  'group_id' => 'required',
  'token' => 'required',
  'user_id' => 'required',
  'email' => 'required|email',
];


  public function generatetoken()
  {
    $this->token = str_random(30);
  }


  public function user()
  {
    $this->belongsTo('\App\User');
  }

  public function group()
  {
    $this->belongsTo('\App\Group');
  }


}
