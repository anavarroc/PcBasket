<?php

namespace PcBasket\Application\Player;

use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Event\PlayerCreated;
use PcBasket\Domain\Player\Exception\PlayerAlreadyExistsException;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerRepositoryInterface;
use PcBasket\Domain\Role\RoleFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreatePlayerHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private RoleFactory $roleFactory;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        RoleFactory $roleFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->playerRepository = $playerRepository;
        $this->roleFactory = $roleFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreatePlayerCommand $command): void
    {
        $this->checkIfPlayerAlreadyExists($command->getNumber());
        $this->playerRepository->save(new Player(
            $command->getNumber(),
            $command->getName(),
            $this->roleFactory->build($command->getRole()),
            new CoachRating($command->getCoachRating())
        ));
        $this->notify($command->getNumber());
    }

    private function checkIfPlayerAlreadyExists(int $playerNumber)
    {
        $player = $this->playerRepository->find($playerNumber);
        if (null !== $player) {
            throw new PlayerAlreadyExistsException($playerNumber);
        }
    }

    private function notify($itemId)
    {
        $this->eventDispatcher->dispatch(new PlayerCreated((string) $itemId));
    }
}
