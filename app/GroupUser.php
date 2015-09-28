<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupUser extends Model {

	protected $table = 'group_user';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function votes()
	{
		return $this->morphedByMany('Vote', 'votable');
	}

}