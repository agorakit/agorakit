<?php

/*
 * This file is part of Laravel Flash.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Flash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Flash.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class Flash extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flash';
    }
}
