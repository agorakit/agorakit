<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;


class Reaction extends Model
{

  protected $fillable = ['reactable_type', 'reactable_id', 'reactor_type', 'reactor_id', 'context'];

  public static function reactTo($model, $reaction_name)
  {
    $reaction = Reaction::firstOrNew([
      'reactable_type' => get_class($model),
      'reactable_id' => $model->id,
      'reactor_type' => 'App\User',
      'reactor_id' => Auth::user()->id
    ]);


    $reaction->context = $reaction_name;

    return $reaction->save();

  }

  public static function unReactTo($model)
  {
    return Reaction::where('reactable_type', get_class($model))
    ->where('reactable_id', $model->id)
    ->where('reactor_type', 'App\User')->where('reactor_id', Auth::user()->id)->delete();
  }

}
