<?php

namespace PcBasket\Application\Common;

use PcBasket\Domain\common\ReadEventCollection;
use PcBasket\Domain\common\ReadEventRepositoryInterface;

class FetchEventHistoryHandler
{
    private ReadEventRepositoryInterface $readEventRepository;

    public function __construct(
        ReadEventRepositoryInterface $readEventRepository
    ) {
        $this->readEventRepository = $readEventRepository;
    }

    public function handle(FetchEventHistoryQuery $query): EventDtoCollection
    {
        $eventCollection = $this->readEventRepository->findAll();

        return $this->eventsToDtoCollection($eventCollection);
    }

    protected function eventsToDtoCollection(ReadEventCollection $eventCollection): EventDtoCollection
    {
        $eventDtoCollection = new EventDtoCollection();
        foreach ($eventCollection as $event) {
            $eventDtoCollection->add(
                EventDto::fromEvent($event)
            );
        }

        return $eventDtoCollection;
    }
}
