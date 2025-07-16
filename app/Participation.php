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
        'calendarevent_id' => 'required|exists:calendarevents,id',
    ];

    protected $fillable = [
        'user_id', 'calendarevent_id', 'notification', 'status'
    ];

    protected $table = 'calendarevent_user';
    public $timestamps = true;

    public function calendarevent()
    {
        return $this->belongsTo(\App\CalendarEvent::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
