<?php

namespace PcBasket\Infrastructure\Persistence\Event;

use PcBasket\Domain\common\ReadEvent;
use PcBasket\Domain\common\ReadEventCollection;
use PcBasket\Domain\common\ReadEventRepositoryInterface;

class MemoryReadEventRepository implements ReadEventRepositoryInterface
{
    private array $items = [];

    public function findAll(): ReadEventCollection
    {
        $players = new ReadEventCollection();

        return $players->addArray($this->items);
    }

    public function save(ReadEvent $event)
    {
        $this->items[] = $event;
    }
}
