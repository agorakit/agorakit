<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model {

	protected $table = 'actions';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function group()
	{
		return $this->belongsTo('Group');
	}

	public function votes()
	{
		return $this->morphMany('Vote', 'votable');
	}

}