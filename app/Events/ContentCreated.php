<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;



/**
 * This event is triggered each time a discussion is created
 */
class ContentCreated
{
    use SerializesModels;


     public $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
       $this->model = $model;
    }


}
