<?php

namespace PcBasket\Infrastructure\Persistence\Event;

use DateTime;
use hanneskod\yaysondb\Engine\FlysystemEngine;
use hanneskod\yaysondb\Yaysondb;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PcBasket\Domain\common\ReadEvent;
use PcBasket\Domain\common\ReadEventCollection;
use PcBasket\Domain\common\ReadEventRepositoryInterface;

class JsonReadEventRepository implements ReadEventRepositoryInterface
{
    const FAKE_DB_DIR = 'src/Infrastructure/Persistence/FakeDB';
    private $table;

    public function __construct()
    {
        $db = new Yaysondb([
            'table' => new FlysystemEngine(
                'events.json',
                new Filesystem(new Local(self::FAKE_DB_DIR))
            ),
        ]);

        $this->table = $db->collection('table');
    }

    public function findAll(): ReadEventCollection
    {
        $events = new ReadEventCollection();
        $this->table->each(
            function ($row) use ($events) {
                $events->add(
                    new ReadEvent(
                        $row['item'],
                        new DateTime($row['date']),
                        $row['name'],
                    )
                );
            }
        );

        return $events;
    }
}
