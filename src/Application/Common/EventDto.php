<?php

namespace PcBasket\Application\Common;

use PcBasket\Domain\common\ReadEvent;

class EventDto
{
    private string $item;
    private string $date;
    private string $name;

    private function __construct(
        string $item,
        string $date,
        string $name
    ) {
        $this->item = $item;
        $this->date = $date;
        $this->name = $name;
    }

    public static function fromEvent(ReadEvent $event)
    {
        return new self(
            $event->which(),
            $event->when()->format('Y-m-d H:i:s'),
            $event->what(),
        );
    }

    public function toArray()
    {
        return [
            'date' => $this->date,
            'name' => $this->name,
            'item' => $this->item,
        ];
    }
}
