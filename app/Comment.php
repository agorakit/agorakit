<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

	protected $fillable = ['body', 'discussion_id', 'parent_id'];


	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];



	public function user()
	{
		return $this->belongsTo('App\User');
	}


	public function votes()
	{
		return $this->morphMany('App\Vote', 'votable');
	}


  public function commentable()
    {
        return $this->morphTo();
    }

}
