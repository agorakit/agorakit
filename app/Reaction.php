<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['reactable_type', 'reactable_id', 'user_id', 'type'];


    /**
     * Adds a reaction to any model
     * $reaction_type must be one of the allowed reactions in config
     */
    public static function reactTo($model, $reaction_type)
    {
        $reaction = Reaction::firstOrNew([
            'reactable_type' => get_class($model),
            'reactable_id'   => $model->id,
            'user_id'     => Auth::user()->id,
        ]);

        if (in_array($reaction_type, setting()->getArray('reactions'))) {
            $reaction->type = $reaction_type;
        } else {
            abort(500, 'invalid reaction type, check config');
        }

        return $reaction->save();
    }

    /**
     * Remove reaction from model
     */
    public static function unReactTo($model)
    {
        return Reaction::where('reactable_type', get_class($model))
            ->where('reactable_id', $model->id)
            ->where('user_id', Auth::user()->id)->delete();
    }

    

   

    /**
     * Returns the model this reaction is related to
     */
    public function reactable()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * Returns the model this reaction is related to
     * Alias of the reactable() function
     */
    public function model()
    {
        return $this->morphTo(__FUNCTION__, 'reactable_type', 'reactable_id')->withTrashed();
    }


    /**
     * Returns the user who reacted
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }
}
