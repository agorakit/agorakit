<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends \Cviebrock\EloquentTaggable\Models\Tag
{
    public function getRouteKeyName()
    {
        return 'normalized';
    }
}
