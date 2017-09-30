<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Activity extends Model
{
    use ValidatingTrait;

    protected $rules = [
    'user_id'  => 'required|exists:users,id',
    'group_id'  => 'required|exists:groups,id',
    'action' => 'required|in:created,updated,deleted',
  ];


    /**
    * Get all of the owning models
    */
    public function model()
    {
        return $this->morphTo()->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group')->withTrashed();
    }



}
