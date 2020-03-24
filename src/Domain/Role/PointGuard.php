<?php

namespace PcBasket\Domain\Role;

class PointGuard extends PlayerRole
{
    const NAME = 'BASE';

    public function __toString(): string
    {
        return self::NAME;
    }
}
