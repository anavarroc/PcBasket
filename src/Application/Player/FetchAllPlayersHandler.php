<?php

namespace PcBasket\Application\Player;

use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Player\PlayerCollectionOrderStrategyInterface;
use PcBasket\Domain\Player\PlayerRepositoryInterface;

class FetchAllPlayersHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private ?PlayerCollectionOrderStrategyInterface $orderStrategy;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        ?PlayerCollectionOrderStrategyInterface $orderStrategy = null
    ) {
        $this->playerRepository = $playerRepository;
        $this->orderStrategy = $orderStrategy;
    }

    public function handle(FetchAllPlayersQuery $query): PlayerDtoCollection
    {
        $playerCollection = $this->playerRepository->findAll();
        $orderedPlayers = $this->orderCollection($playerCollection);

        return $this->playersToDtoCollection($orderedPlayers);
    }

    private function orderCollection(PlayerCollection $playerCollection): PlayerCollection
    {
        if (null !== $this->orderStrategy) {
            return $this->orderStrategy->execute($playerCollection);
        }

        return $playerCollection;
    }

    private function playersToDtoCollection(PlayerCollection $playerCollection)
    {
        $dtoCollection = new PlayerDtoCollection();
        foreach ($playerCollection as $player) {
            $dtoCollection->add(
                PlayerDto::fromPlayer($player)
            );
        }

        return $dtoCollection;
    }
}
