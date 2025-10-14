<?php

namespace App\Contracts;

interface ImapMessageInterface
{
    public function getSubject(): string;
    public function getFrom(): string;
    public function getBodyHtml(): string;
    public function getBodyText(): string;
    public function getRecipients(): array;
    public function move(string $folder);
    public function isAutomated(): bool;
}
