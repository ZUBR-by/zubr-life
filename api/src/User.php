<?php

namespace App;

class User
{
    private int $id;
    private bool $isBanned;

    public function __construct(int $id, bool $isBanned = false)
    {
        $this->id = $id;
        $this->isBanned = $isBanned;
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public static function empty(): self
    {
        return new self(0);
    }

    public function isEmpty(): bool
    {
        return $this->id === 0;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function isEqual(?int $user_id): bool
    {
        if ($user_id === null) {
            return false;
        }
        if ($this->isEmpty()) {
            return false;
        }
        return $this->id === $user_id;
    }
}
