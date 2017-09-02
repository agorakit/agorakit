<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Carbon\Carbon;
use Geocoder\Laravel\Facades\Geocoder;

class Action extends Model
{


  use ValidatingTrait;
  use RevisionableTrait;
  use SoftDeletes;

  protected $fillable = ['id']; // neede for actions import

  protected $rules = [
    'name' => 'required|min:5',
    'user_id' => 'required|exists:users,id',
    'group_id' => 'required|exists:groups,id',
    'start' => 'required',
    'stop' => 'required',
  ];

  protected $touches = ['group', 'user'];

  protected $table = 'actions';
  public $timestamps = true;
  protected $dates = ['deleted_at', 'start', 'stop'];
  protected $casts = [ 'user_id' => 'integer' ];



  public function group()
  {
    return $this->belongsTo('\App\Group');
  }

  public function user()
  {
    return $this->belongsTo('\App\User');
  }

  public function votes()
  {
    return $this->morphMany('Vote', 'votable');
  }

  public function link()
  {
      return action('ActionController@show', [$this->group, $this]);
  }


  /**
  * Geocode the item
  * Returns true if it worked, false if it didn't
  */
  public function geocode()
  {

      if ($this->location == '')
      {
          $this->latitude = 0;
          $this->longitude = 0;
          return true;
      }

      try
      {
          $geocode = Geocoder::geocode($this->location)->get()->first();
      }
      catch (\Exception $e)
      {
          return false;
      }

      $this->latitude = $geocode->getLatitude();
      $this->longitude = $geocode->getLongitude();
      return true;
  }


}
