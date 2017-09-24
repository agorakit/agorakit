<?php
namespace App\Traits;

use Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model)
        {
            $activity = new \App\Activity;
            $activity->action = 'created';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->save();

        });

        static::updated(function ($model) {
            $activity = new \App\Activity;
            $activity->action = 'updated';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->save();
        });

        static::deleted(function ($model) {
            $activity = new \App\Activity;
            $activity->action = 'deleted';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->save();
        });
    }
}
