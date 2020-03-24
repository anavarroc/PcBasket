<?php

namespace PcBasket\Domain\common;

use DateTime;

class WriteEvent
{
    private DateTime $when;
    private string $itemId;

    public function __construct($itemId)
    {
        $this->when = new DateTime();
        $this->itemId = $itemId;
    }

    public function which(): string
    {
        return $this->itemId;
    }

    public function when(): DateTime
    {
        return $this->when;
    }

    public function what(): string
    {
        $path = explode('\\', static::class);

        return array_pop($path);
    }
}
