<?php

namespace App\Traits;

interface ContentStatus
{
    public const NORMAL = 0;
    public const PINNED = 10;
    public const ARCHIVED = -10;
}
