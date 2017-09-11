<?php

/*
 * This file is part of Laravel Flash.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Tests\Flash\Facades;

use DraperStudio\Flash\Facades\Flash;
use DraperStudio\Flash\FlashNotifier;
use DraperStudio\Tests\Flash\AbstractTestCase;
use GrahamCampbell\TestBenchCore\FacadeTrait;

/**
 * This is the facade test class.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class FlashTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'flash';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return Flash::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return FlashNotifier::class;
    }
}
