<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use App\Discussion;
use App\File;
use App\Action;

class Activity extends Model
{
    use ValidatingTrait;

    protected $rules = [
    'user_id'  => 'required|exists:users,id',
    'group_id'  => 'required|exists:groups,id',
    'action' => 'required|in:created,updated,deleted',
  ];


    /**
    * Get all of the owning models
    */
    public function model()
    {
        return $this->morphTo()->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group')->withTrashed();
    }

    /**
     * Little helper to returna link to the related model
     */
    public function linkToModel()
    {

        if ($this->model instanceof Discussion)
        {
            return action('DiscussionController@show', [$this->group, $this->model]);
        }

        if ($this->model instanceof File)
        {
            return action('FileController@show', [$this->group, $this->model]);
        }

        if ($this->model instanceof Action)
        {
            return action('ActionController@show', [$this->group, $this->model]);
        }


    }


}
