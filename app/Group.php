<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Discussion;
use Watson\Validating\ValidatingTrait;
use Auth;


class Group extends Model
{
  use ValidatingTrait;

  protected $rules = [
     'name' => 'required',
     'body' => 'required'
 ];

    //protected $with = ['membership'];

    protected $fillable = ['name', 'body', 'cover'];


    /**
     * Returns all the users of this group
     *
     */
    public function users()
    {
      return $this->belongsToMany('App\User', 'membership')->withTimestamps();
    }

    /**
     * Returns all the discussions belonging to this group
     *
     */
    public function discussions()
    {
      return $this->hasMany('App\Discussion');
    }


    /**
     * Returns all the actions belonging to this group
     *
     */
    public function actions()
    {
      return $this->hasMany('App\Action');
    }


    public function files()
    {
      return $this->hasMany('App\File');
    }

    /**
    * Returns membership info for the given user
    * Default to curently logged user if not provided
    * Returns false if no membership found
    */
    public function isMember(User $user = null)
    {


      if (is_null ($user))
      {
        $user = $user = Auth::user();
      }

      // TODO eager loading, but HOW ?!?

      if ($user)
      {
        $membership = \App\Membership::where('user_id', $user->id)->where('group_id', $this->id)->first();

        if ($membership && $membership->membership > 1)
        {
          return true;
        }
      }

      return false;
    }


  }
