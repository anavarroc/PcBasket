<?php

namespace PcBasket\Domain\Role;

abstract class PlayerRole
{
    const NAME = '';

    public function isEqual(self $playerRole): bool
    {
        return (string) $this === (string) $playerRole;
    }

    public function __toString(): string
    {
        return static::NAME;
    }
}
