<?php

namespace App\Contracts;

interface ImapServerInterface
{
    public function getMailboxes();

    public function getMessages();

    public function touchFolder($folderName);

    public function expunge();
}
