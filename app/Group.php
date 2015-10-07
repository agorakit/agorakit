<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Discussion;


class Group extends Model
{

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
