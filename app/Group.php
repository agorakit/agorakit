<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Discussion;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Auth;


class Group extends Model
{
  use ValidatingTrait;
  use RevisionableTrait;
  use SoftDeletes;

  protected $rules = [
    'name' => 'required',
    'body' => 'required'
  ];

  protected $fillable = ['id', 'name', 'body', 'cover'];
  protected $casts = [ 'user_id' => 'integer' ];


  /**
   * Returns the css color (yes) of this group. Curently random generated
   */
  public function color()
  {

    if ($this->color)
    {
      return $this->color;
    }
    else
    {
      $this->color = 'rgb(' . rand(0, 200) . ' , ' . rand(0, 200) . ' , ' . rand(0, 200) . ')';
      $this->save();
      return $this->color;
    }
  }


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


  public function link()
  {
      return action('GroupController@show', $this);
  }

}
