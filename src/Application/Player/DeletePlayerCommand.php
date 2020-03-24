<?php

namespace PcBasket\Application\Player;

class DeletePlayerCommand
{
    private int $number;

    public function __construct(
        int $number
    ) {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
