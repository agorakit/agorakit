<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use App\Action;

class Location extends Model
{
    use ValidatingTrait;

    protected $rules = [
        'name'          => 'required',
    ];

    /**
    * Generates a random color if none is set, and saves the location.
    */
    public function getColorAttribute($value)
    {
        if ($value) {
            return $value;
        } else {
            $color = sprintf("#%06x",rand(0,16777215));
            $this->color = $color;
            $this->save();
            return $color;
        }
    }
}
