<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Action extends Model implements \MaddHatter\LaravelFullcalendar\Event
{


  use ValidatingTrait;


  protected $rules = [
    'name' => 'required|min:5',
    'user_id' => 'required',
    'start' => 'required',
    'stop' => 'required',
  ];

    protected $table = 'actions';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at', 'start', 'stop'];

		/**
		* Get the event's id number
		*
		* @return int
		*/
	 public function getId() {
			 return $this->id;
	 }

	 /**
		* Get the event's title
		*
		* @return string
		*/
	 public function getTitle()
	 {
			 return $this->name;
	 }

	 /**
		* Is it an all day event?
		*
		* @return bool
		*/
	 public function isAllDay()
	 {
		 return false;
			 //return (bool)$this->all_day;
	 }

	 /**
		* Get the start time
		*
		* @return DateTime
		*/
	 public function getStart()
	 {
			 return $this->start;
	 }

	 /**
		* Get the end time
		*
		* @return DateTime
		*/
	 public function getEnd()
	 {
			 return $this->stop;
	 }


    public function group()
    {
        return $this->belongsTo('Group');
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function votes()
    {
        return $this->morphMany('Vote', 'votable');
    }
}
