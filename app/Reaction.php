<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['reactable_type', 'reactable_id', 'user_id', 'type'];

    public static function reactTo($model, $reaction_type)
    {
        $reaction = Reaction::firstOrNew([
            'reactable_type' => get_class($model),
            'reactable_id'   => $model->id,
            'user_id'     => Auth::user()->id,
        ]);

        $reaction->type = $reaction_type;

        return $reaction->save();
    }

    public static function unReactTo($model)
    {
        return Reaction::where('reactable_type', get_class($model))
            ->where('reactable_id', $model->id)
            ->where('user_id', Auth::user()->id)->delete();
    }

    public function reactable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function model()
    {
        return $this->morphTo(__FUNCTION__, 'reactable_type', 'reactable_id')->withTrashed();
    }



    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
