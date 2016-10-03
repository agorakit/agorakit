<?php

/*
 * This file is part of Laravel Flash.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('flash')) {
    /**
     * @param null $message
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function flash($message = null)
    {
        $notifier = app('flash');

        if (!is_null($message)) {
            return $notifier->info($message);
        }

        return $notifier;
    }
}
