<?php

namespace PcBasket\Domain\Tactic;

use PcBasket\Domain\Player\PlayerCollection;

class Tactic
{
    private string $name;
    private PlayerCollection $players;

    public function __construct(
        $name,
        PlayerCollection $players
    ) {
        $this->name = $name;
        $this->players = $players;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getName()
    {
        return $this->name;
    }
}
