<?php

namespace Agorakit\Traits;

use Agorakit\Scopes\HasStatusScope;
use Illuminate\Database\Eloquent\Builder;

interface ContentStatus
{
    const NORMAL = 0;
    const PINNED = 10;
    const ARCHIVED = -10;
}

trait HasStatus
{

    public function getStatus(): int
    {
        return $this->{'status'};
    }

    public function setStatus(int $status)
    {
        $this->{'status'} = $status;
        return $this;
    }

    public function isPinned(): bool
    {
        return $this->getStatus() === ContentStatus::PINNED;
    }

    public function pin(): self
    {
        return $this->setStatus(ContentStatus::PINNED);
    }

    public function unPin(): self
    {
        return $this->setStatus(ContentStatus::NORMAL);
    }

    public function togglePin(): self
    {
        if ($this->isPinned()) {
            $this->unPin();
        } else {
            $this->pin();
        }
        return $this;
    }

    public function isArchived(): bool
    {
        return $this->getStatus() === ContentStatus::ARCHIVED;
    }

    public function archive(): self
    {
        return $this->setStatus(ContentStatus::ARCHIVED);
    }

    public function unArchive(): self
    {
        return $this->setStatus(ContentStatus::NORMAL);
    }

    public function toggleArchive(): self
    {
        if ($this->isArchived()) {
            $this->unArchive();
        } else {
            $this->archive();
        }
        return $this;
    }

}
