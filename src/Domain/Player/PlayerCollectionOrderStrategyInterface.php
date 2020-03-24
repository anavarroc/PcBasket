<?php

namespace PcBasket\Domain\Player;

interface PlayerCollectionOrderStrategyInterface
{
    public function execute(PlayerCollection $players): PlayerCollection;
}
