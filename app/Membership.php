<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'membership';
    public $timestamps = true;
    protected $fillable = ['group_id', 'user_id', 'membership'];

    protected $dates = ['notifed_at'];

    protected $rules = [
    'user_id' => 'required|exists:users,id',
    'group_id' => 'required|exists:groups,id',
    ];

  


    public function votes()
    {
        return $this->morphedByMany('Vote', 'votable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
