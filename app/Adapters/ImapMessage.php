<?php

namespace App\Adapters;

use App\Contracts\ImapMessageInterface;

class ImapMessage implements ImapMessageInterface
{
    protected object $message;

    public function __construct(object $message)
    {
        if (class_exists('Ddeboer\Imap\Message') && is_a($message, 'Ddeboer\Imap\Message')) {
            $this->message = $message;
        }
    }

    public function getSubject(): string
    {
        return $this->message->getSubject();
    }
    public function getFrom(): string
    {
        return $this->message->getFrom()->getAddress();
    }
    public function getBodyHtml(): string
    {
        return $this->message->getBodyHtml();
    }
    public function getBodyText(): string
    {
        return $this->message->getBodyText();
    }

    /**
     * All email addresses that received the message for any reason.
     *
     * @return array
     */
    public function getRecipients(): array
    {
        $recipients = [];
        foreach ($this->message->getTo() as $to) {
            $recipients[] = $to->getAddress();
        }
        foreach ($this->message->getCc() as $to) {
            $recipients[] = $to->getAddress();
        }
        foreach ($this->message->getInReplyTo() as $to) {
            $recipients[] = $to;
        }
        foreach ($this->message->getReferences() as $to) {
            $recipients[] = $to;
        }

        return $recipients;
    }

    public function move(string $folder)
    {
        return $this->message->move($folder);
    }

    /**
     * Returns RFC822 headers of the email as key => value
     *
     * Reference for base & improved regex:
     * @see https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap/5631445#5631445
     * @see https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap#comment61912182_5631445
     */
    protected function parseHeaders(string $header_string): array
    {
        preg_match_all(
            '/([^:\s]+): (.*?(?:\r\n\s(?:.+?))*)\r\n/m',
            $header_string,
            $matches
        );
        return array_combine($matches[1], $matches[2]);
    }

    /**
     * Returns true if message is an autoreply or vacation auto responder
     */
    public function isAutomated(): bool
    {
        $automated = false;
        $headers = $this->parseHeaders($this->message->getRawHeaders());

        // Detect known automation headers.
        $knownAutoHeaders = [
            'Auto-Submitted',
            'Auto-submitted',
            'X-Auto-Response-Suppress',
            'List-Id',
            'List-Unsubscribe',
            'Feedback-ID',
            'X-NIDENT',
            'X-AG-AUTOREPLY',
        ];
        if (count(array_intersect(array_keys($headers), $knownAutoHeaders))) {
            $automated = true;
        }

        // Detect Autoresponders.
        if (array_key_exists('Delivered-To', $headers) && $headers['Delivered-To'] == 'Autoresponder') {
            $automated = true;
        }

        return $automated;
    }
}
