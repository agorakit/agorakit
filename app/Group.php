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

  use \Venturecraft\Revisionable\RevisionableTrait;

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
    return $this->belongsToMany('App\User', 'membership')->withTimestamps();
  }

  /**
   * The user who crreated or updated this group title and description
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  /**
  * return membership for the current user
  */
  public function membership()
  {
    if (\Auth::check())
    {
      return $this->belongsToMany('App\User', 'membership')
      ->where('user_id', "=", \Auth::user()->id)
      ->withPivot('membership');
    }
    else
    {
      //return false;
    }
  }


  public function memberships()
  {
      return $this->hasMany('App\Membership');
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
  *	Returns true if current user is a member of this group
  */
  public function isMember()
  {
    if (\Auth::check())
    {
      $member = $this->membership->first();
      if ($member && $member->pivot->membership > 10)
      {
      return true;
      }
    }

    return false;
  }


  /**
  * Returns membership info for curently logged user
  * Returns false if no membership found
  */
  public function getMembership()
  {
    if (\Auth::check())
    {
      $member = $this->membership->first();


      if ($member )
      {

        return $member->pivot->membership;
      }
    }
    return false;
  }


}
