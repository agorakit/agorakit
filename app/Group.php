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




  protected $fillable = ['id', 'name', 'body', 'cover'];


  /**
   * Returns a summary of this item of $length
   */
  public function summary($length = 200)
  {
    return str_limit(strip_tags($this->body), $length);
  }

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

      //$this->color = 'rgb(' . rand(0, 200) . ' , ' . rand(0, 200) . ' , ' . rand(0, 200) . ')';
      $this->color = 'rgb('. $this->hsvToRGB(rand (0, 360), 60, 75) . ')';
      $this->save();
      return $this->color;
    }
  }

  function hsvToRGB($iH, $iS, $iV) {
        if($iH < 0)   $iH = 0;   // Hue:
        if($iH > 360) $iH = 360; //   0-360
        if($iS < 0)   $iS = 0;   // Saturation:
        if($iS > 100) $iS = 100; //   0-100
        if($iV < 0)   $iV = 0;   // Lightness:
        if($iV > 100) $iV = 100; //   0-100
        $dS = $iS/100.0; // Saturation: 0.0-1.0
        $dV = $iV/100.0; // Lightness:  0.0-1.0
        $dC = $dV*$dS;   // Chroma:     0.0-1.0
        $dH = $iH/60.0;  // H-Prime:    0.0-6.0
        $dT = $dH;       // Temp variable
        while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
        $dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link
        switch(floor($dH)) {
            case 0:
                $dR = $dC; $dG = $dX; $dB = 0.0; break;
            case 1:
                $dR = $dX; $dG = $dC; $dB = 0.0; break;
            case 2:
                $dR = 0.0; $dG = $dC; $dB = $dX; break;
            case 3:
                $dR = 0.0; $dG = $dX; $dB = $dC; break;
            case 4:
                $dR = $dX; $dG = 0.0; $dB = $dC; break;
            case 5:
                $dR = $dC; $dG = 0.0; $dB = $dX; break;
            default:
                $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
        }
        $dM  = $dV - $dC;
        $dR += $dM; $dG += $dM; $dB += $dM;
        $dR *= 255; $dG *= 255; $dB *= 255;
        return round($dR).",".round($dG).",".round($dB);
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


}
