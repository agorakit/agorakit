<?php

namespace App\Traits;

use App\Activity;
use App\Comment;
use Auth;

/************* candidate for REMOVAL TODO ************************/

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $activity = new Activity();
            $activity->action = 'created';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);

            if ($model instanceof Comment) { // who cares about leaky abstractions ;-)
                $activity->action = 'commented';
                $activity->group()->associate($model->discussion->group);
            } else {
                $activity->group()->associate($model->group);
            }
            $activity->save();
        });

        static::updated(function ($model) {
            $activity = new Activity();
            $activity->action = 'updated';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            if ($model instanceof Comment) {
                $activity->group()->associate($model->discussion->group);
            } else {
                $activity->group()->associate($model->group);
            }
            $activity->save();
        });

        static::deleted(function ($model) {
            $activity = new Activity();
            $activity->action = 'deleted';
            $activity->user()->associate(Auth::user());
            $activity->model()->associate($model);
            if ($model instanceof Comment) {
                $activity->group()->associate($model->discussion->group);
            } else {
                $activity->group()->associate($model->group);
            }
            $activity->save();
        });
    }
}
