<?php

namespace PcBasket\Domain\Player;

interface PlayerRepositoryInterface
{
    public function save(Player $player);

    public function find($playerNumber): ?Player;

    public function delete(Player $player);

    public function findAll(): PlayerCollection;
}
