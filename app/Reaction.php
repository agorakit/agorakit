<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['reactable_type', 'reactable_id', 'user_id', 'type'];

    public static function reactTo($model, $reaction)
    {
        $reaction = Reaction::firstOrNew([
            'reactable_type' => get_class($model),
            'reactable_id'   => $model->id,
            'user_id'     => Auth::user()->id,
        ]);

        $reaction->type = $reaction;

        return $reaction->save();
    }

    public static function unReactTo($model)
    {
        return Reaction::where('reactable_type', get_class($model))
            ->where('reactable_id', $model->id)
            ->where('user_id', Auth::user()->id)->delete();
    }

    public function model()
    {
        return $this->morphTo()->withTrashed();
    }

    public function modelB()
    {
    return $this->morphedByMany($class, 'taggable', $table, 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
