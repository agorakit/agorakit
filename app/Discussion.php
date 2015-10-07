<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Comment;

class Discussion extends Model {

	protected $table = 'discussions';
	protected $fillable = ['name', 'body', 'group_id', 'parent_id'];


	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function group()
	{
		return $this->belongsTo('App\Group');
	}


	public function user()
	{
		return $this->belongsTo('App\User');
	}


	public function votes()
	{
		return $this->morphMany('App\Vote', 'votable');
	}

	public function comments()
	{
	return $this->morphMany('App\Comment', 'commentable');
	}

}
