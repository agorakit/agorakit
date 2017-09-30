<?php
namespace App\Traits;

use App\Activity;
use Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model)
        {
            $activity = new Activity;
            $activity->action = 'created';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->saveORFail();

        });

        static::updated(function ($model) {
            $activity = new Activity;
            $activity->action = 'updated';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->saveORFail();
        });

        static::deleted(function ($model) {
            $activity = new Activity;
            $activity->action = 'deleted';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            $activity->group()->associate($model->group);
            $activity->saveORFail();
        });
    }
}
