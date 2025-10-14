<?php

namespace App\Adapters;

use App\Contracts\ImapServerInterface;

class ImapServer implements ImapServerInterface
{
    protected static ImapServer $imapserver;

    protected object $ddeboer;

    /**
     * @return ImapServer
     */
    public static function getInstance(): object
    {
        if (self::$imapserver !== null) {
            return self::$imapserver;
        } else {
            self::$imapserver = new ImapServer();
            self::$imapserver->createServer();
        }
        return self::$imapserver;
    }

    protected function createServer()
    {
        if (class_exists('Ddeboer\Imap\Server')) {
            $this->ddeboer = (new Ddeboer\Imap\Server( // @phpstan-ignore class.notFound
                config('agorakit.inbox_host'),
                config('agorakit.inbox_port'),
                config('agorakit.inbox_flags')
            ))->authenticate(
                config('agorakit.inbox_username'),
                config('agorakit.inbox_password')
            );
        }
    }

    public function expunge()
    {
        self::getInstance()->ddeboer->expunge();
    }

    public function getMailboxes(): iterable
    {
        return self::getInstance()->ddeboer->getMailboxes();
    }

    public function getMessages(): iterable
    {
        return self::getInstance()->ddeboer->getMailbox('INBOX')->getMessages();
    }

    /**
     * Get mailbox folder (create if it doesn't exist).
     */
    public function touchFolder($folderName)
    {
        if (self::getInstance()->ddeboer->hasMailbox($folderName)) {
            $folder = self::getInstance()->ddeboer->getMailbox($folderName);
        } else {
            $folder = self::getInstance()->ddeboer->createMailbox($folderName);
        }
        return $folder;
    }
}
