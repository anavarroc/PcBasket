<?php

namespace PcBasket\Infrastructure\Persistence\Event;

use hanneskod\yaysondb\Engine\FlysystemEngine;
use hanneskod\yaysondb\Yaysondb;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PcBasket\Domain\common\WriteEvent;
use PcBasket\Domain\common\WriteEventRepositoryInterface;

class JsonWriteEventRepository implements WriteEventRepositoryInterface
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

    public function save(WriteEvent $event)
    {
        $this->table->insert(
            [
                    'item' => $event->which(),
                    'name' => $event->what(),
                    'date' => $event->when()->format('Y-m-d H:i:s'),
            ]
        );
        $this->table->commit();
    }
}
