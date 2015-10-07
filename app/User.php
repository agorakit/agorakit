<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Group;

class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
  use Authenticatable, Authorizable, CanResetPassword;

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'users';

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = ['name', 'email', 'password'];

  /**
  * The attributes excluded from the model's JSON form.
  *
  * @var array
  */
  protected $hidden = ['password', 'remember_token'];

/**
 * The groups this user is part of
 *
 */
  public function groups()
  {
    return $this->belongsToMany('App\Group')->withTimestamps();
  }

  /**
   * Discussions by this user
   *
   */
  public function discussions()
	{
		return $this->hasMany('App\Discussion');
	}


}
