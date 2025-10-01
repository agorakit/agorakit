<?php

namespace App\Traits;

interface ContentVisibility
{
    public const INHERITED = 0;
    public const PUBLIC = 10;
    public const PRIVATE = -10;
}
