<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Discussion;
use Watson\Validating\ValidatingTrait;


class Group extends Model
{
  use ValidatingTrait;

  protected $rules = [
     'name' => 'required',
     'body' => 'required'
 ];

    protected $fillable = ['name', 'body', 'cover'];


    /**
     * Returns all the users of this group
     *
     */
    public function users()
    {
      return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Returns all the discussions belonging to this group
     *
     */
    public function discussions()
    {
      return $this->hasMany('App\Discussion');
    }

    public function files()
    {
      return $this->hasMany('App\File');
    }


}
