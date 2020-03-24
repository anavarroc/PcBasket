<?php

namespace PcBasket\Infrastructure\Persistence\Event;

use PcBasket\Domain\common\WriteEvent;
use PcBasket\Domain\common\WriteEventRepositoryInterface;

class MemoryWriteEventRepository implements WriteEventRepositoryInterface
{
    private array $items = [];

    public function findAll(): array
    {
        return $this->items;
    }

    public function save(WriteEvent $event)
    {
        $this->items[] = $event;
    }
}
