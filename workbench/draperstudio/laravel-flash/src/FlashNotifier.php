<?php

/*
 * This file is part of Laravel Flash.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Flash;

use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;

/**
 * Class FlashNotifier.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class FlashNotifier
{
    /**
     * @var Store
     */
    private $session;

    /**
     * @var array
     */
    private $messages;

    /**
     * FlashNotifier constructor.
     *
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * @param $message
     * @param null $title
     *
     * @return FlashNotifier
     */
    public function success($message, $title = null)
    {
        return $this->message($message, 'success', $title);
    }

    /**
     * @param $message
     * @param null $title
     *
     * @return FlashNotifier
     */
    public function info($message, $title = null)
    {
        return $this->message($message, 'info', $title);
    }

    /**
     * @param $message
     * @param null $title
     *
     * @return FlashNotifier
     */
    public function warning($message, $title = null)
    {
        return $this->message($message, 'warning', $title);
    }

    /**
     * @param $message
     * @param null $title
     *
     * @return FlashNotifier
     */
    public function error($message, $title = null)
    {
        return $this->message($message, 'danger', $title);
    }

    /**
     * @return $this
     */
    public function important()
    {
        $this->session->flash('flash_notification.important', true);

        return $this;
    }

    /**
     * @param $message
     * @param string $title
     *
     * @return FlashNotifier
     */
    public function overlay($message, $title = 'Notice')
    {
        return $this->message($message, 'info', $title, true);
    }

    /**
     * @param $message
     * @param string $level
     * @param string $title
     * @param bool   $overlay
     *
     * @return $this
     */
    public function message($message, $level = 'info', $title = 'Notice', $overlay = false)
    {
        if (is_array($message)) {
            $message = new MessageBag($message);
        }

        $values = $this->session->get('flash_notification.messages', []);
        $values[] = compact('message', 'level', 'title', 'overlay');
        $this->session->flash('flash_notification.messages', $values);

        return $this;
    }

}
