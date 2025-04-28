<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

/** 
 * Wether a user participates or not to a specific event
 */
class Participation extends Model
{
    use ValidatingTrait;
    use SoftDeletes;

    const UNDECIDED = 0;
    const PARTICIPATE = 10;
    const WONT_PARTICIPATE = -10;

    protected $attributes = [
        'status' => self::PARTICIPATE,
        'notification' => 60,
    ];

    protected $rules = [
        'user_id'   => 'required|exists:users,id',
        'event_id' => 'required|exists:events,id',
    ];

    protected $fillable = [
        'user_id', 'event_id', 'notification', 'status'
    ];

    protected $table = 'event_user';
    public $timestamps = true;

    public function event()
    {
        return $this->belongsTo(\App\Event::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
