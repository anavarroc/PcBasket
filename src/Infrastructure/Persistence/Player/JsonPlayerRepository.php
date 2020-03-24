<?php

namespace PcBasket\Infrastructure\Persistence\Player;

use hanneskod\yaysondb\Engine\FlysystemEngine;
use hanneskod\yaysondb\Operators;
use hanneskod\yaysondb\Yaysondb;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Player\PlayerRepositoryInterface;
use PcBasket\Domain\Role\RoleFactory;

class JsonPlayerRepository implements PlayerRepositoryInterface
{
    const FAKE_DB_DIR = 'src/Infrastructure/Persistence/FakeDB';
    private $table;
    private $roleFactory;

    public function __construct(RoleFactory $roleFactory)
    {
        $db = new Yaysondb([
            'table' => new FlysystemEngine(
                'players.json',
                new Filesystem(
                    new Local(self::FAKE_DB_DIR))
            ),
        ]);

        $this->table = $db->collection('table');
        $this->roleFactory = $roleFactory;
    }

    public function save(Player $player): void
    {
        $this->table->insert(
            [
                'number' => $player->getNumber(),
                'name' => $player->getName(),
                'role' => (string) $player->getRole(),
                'rating' => $player->getCoachRating()->getRating(),
            ]
        );
        $this->table->commit();
    }

    public function delete(Player $player): void
    {
        $this->table->delete(
            Operators::doc(['number' => Operators::same($player->getNumber())])
        );
        $this->table->commit();
    }

    public function find($playerNumber): ?Player
    {
        $result = $this->table->findOne(Operators::doc(['number' => Operators::equals($playerNumber)]));
        if (empty($result)) {
            return null;
        }

        return new Player(
            $result['number'],
            $result['name'],
            $this->roleFactory->build($result['role']),
            new CoachRating($result['rating'])
        );
    }

    public function findAll(): PlayerCollection
    {
        $players = new PlayerCollection();
        $this->table->each(
            function ($row) use ($players) {
                $players->add(
                    new Player(
                        $row['number'],
                        $row['name'],
                        $this->roleFactory->build($row['role']),
                        new CoachRating($row['rating'])
                    )
                );
            }
        );

        return $players;
    }
}
