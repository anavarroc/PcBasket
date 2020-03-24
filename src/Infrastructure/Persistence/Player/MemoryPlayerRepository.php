<?php

namespace PcBasket\Infrastructure\Persistence\Player;

use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Player\PlayerRepositoryInterface;

class MemoryPlayerRepository implements PlayerRepositoryInterface
{
    private array $items = [];

    public function save(Player $player)
    {
        $this->items[$player->getNumber()] = $player;
    }

    public function find($playerNumber): ?Player
    {
        if (isset($this->items[$playerNumber])) {
            return $this->items[$playerNumber];
        }

        return null;
    }

    public function delete(Player $player)
    {
        unset($this->items[$player->getNumber()]);
    }

    public function findAll(): PlayerCollection
    {
        $players = new PlayerCollection();

        return $players->addArray($this->items);
    }
}
