<?php

namespace Agorakit\Facades;


use Illuminate\Support\Facades\Facade;

class Context extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Agorakit\Services\ContextService::class;
    }
}
