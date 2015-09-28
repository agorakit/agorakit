<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model {

	protected $table = 'discussions';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function group()
	{
		return $this->belongsTo('Group');
	}

	public function parent()
	{
		return $this->hasOne('Discussion', 'parent_id');
	}

	public function votes()
	{
		return $this->morphMany('Vote', 'votable');
	}

}