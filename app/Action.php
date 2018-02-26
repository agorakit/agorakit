<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use App\Traits\LogsActivity;

class Action extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = ['id']; // neede for actions import

    protected $rules = [
        'name'     => 'required|min:5',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
        'start'    => 'required',
        'stop'     => 'required',
    ];

    //protected $touches = ['group', 'user'];

    protected $table = 'actions';
    public $timestamps = true;
    protected $dates = ['deleted_at', 'start', 'stop'];
    protected $casts = ['user_id' => 'integer'];

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
        return route('groups.actions.show', [$this->group, $this]);
    }

    /**
    * Geocode the item
    * Returns true if it worked, false if it didn't.
    */
    public function geocode()
    {
        if ($this->location == '') {
            $this->latitude = 0;
            $this->longitude = 0;

            return true;
        }


        $geocode = app('geocoder')->geocode($this->location)->get()->first();
        if ($geocode)
        {
            $this->latitude = $geocode->getCoordinates()->getLatitude();
            $this->longitude = $geocode->getCoordinates()->getLongitude();
            return true;
        }

        return false;
    }
}
