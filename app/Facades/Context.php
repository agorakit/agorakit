<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Context extends Facade
{
    public static function getFacadeAccessor()
    {
        return \App\Services\ContextService::class;
    }
}
