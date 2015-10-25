<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReadDiscussion extends Model
{
  protected $table = 'user_read_discussion';

  protected $fillable = ['user_id', 'discussion_id'];

  public $timestamps = false;

  

}
