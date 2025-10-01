<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

interface ContentVisibility
{
    const INHERITED = 0;
    const PUBLIC = 10;
    const PRIVATE = -10;
}

trait HasVisibility
{
    public function getVisibility()
    {
        return $this->{'visibility'};
    }

    public function setVisibility(int $visibility)
    {
        $this->{'visibility'} = $visibility;
        return $this;
    }

    public function isPublic(): bool
    {
        if ($this->group->isOpen()) {
            return true;
        }

        return $this->getVisibility() === ContentVisibility::PUBLIC;
    }

    public function isPrivate(): bool
    {
        if ($this->group->isOpen()) {
            return false;
        }
        return $this->getVisibility() === ContentVisibility::PRIVATE;
    }

    public function makePublic(): self
    {
        return $this->setVisibility(ContentVisibility::PUBLIC);
    }

    public function makePrivate(): self
    {
        return $this->setVisibility(ContentVisibility::PRIVATE);
    }


    public function scopePublic(Builder $query): void
    {
        $query->where('visibility', '=', ContentVisibility::PUBLIC);
    }

    public function scopePrivate(Builder $query): void
    {
        $query->where('visibility', '=', ContentVisibility::PRIVATE);
    }
}
