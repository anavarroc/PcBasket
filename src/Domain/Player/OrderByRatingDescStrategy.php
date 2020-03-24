<?php

namespace PcBasket\Domain\Player;

class OrderByRatingDescStrategy implements PlayerCollectionOrderStrategyInterface
{
    public function execute(PlayerCollection $players): PlayerCollection
    {
        $orderedPlayers = new PlayerCollection();
        $orderedPlayers->addArray(
            $this->sortByRating($players)
        );

        return $orderedPlayers;
    }

    public function sortByRating(PlayerCollection $players): array
    {
        $playersArray = [];
        foreach ($players as $player) {
            $playersArray[] = $player;
        }

        usort($playersArray, fn ($a, $b) => $b->getCoachRating()->getRating() >
            $a->getCoachRating()->getRating());

        return $playersArray;
    }
}
