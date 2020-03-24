<?php

namespace PcBasket\Domain\common;

use DateTime;

class ReadEvent
{
    private DateTime $when;
    private string $itemId;
    private string $what;

    public function __construct(
        string $itemId,
        DateTime $when,
        string $what
    ) {
        $this->itemId = $itemId;
        $this->when = $when;
        $this->what = $what;
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
        return $this->what;
    }
}
