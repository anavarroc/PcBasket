<?php

namespace PcBasket\Application\Player;

use PcBasket\Domain\Player\Event\PlayerDeleted;
use PcBasket\Domain\Player\Exception\PlayerNotFoundException;
use PcBasket\Domain\Player\PlayerRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeletePlayerHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->playerRepository = $playerRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(DeletePlayerCommand $command): void
    {
        $player = $this->findOrFail($command->getNumber());
        $this->playerRepository->delete($player);
        $this->notify($command->getNumber());
    }

    private function findOrFail(int $playerNumber)
    {
        $player = $this->playerRepository->find($playerNumber);
        if (null === $player) {
            throw new PlayerNotFoundException($playerNumber);
        }

        return $player;
    }

    private function notify($itemId)
    {
        $this->eventDispatcher->dispatch(new PlayerDeleted((string) $itemId));
    }
}
