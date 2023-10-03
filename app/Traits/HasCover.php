<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

use App\Setting;
use App\User;
use App\Group;
use App\Discussion;
use App\File;
use App\Action;
use App\Tag;
use Exception;

/** 
 * This trait allows any model to have an image cover 
 * - stored in [model type]/[model id]/cover.jpg 
 */
trait HasCover
{
    // size can be small medium large square
    public function getCover($size = 'medium')
    {

    }

    public function setCover($request)
    {
        
    }


}
