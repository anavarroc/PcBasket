<?php

namespace Tests\Domain\Player;

use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Role\RoleFactory;
use PHPUnit\Framework\TestCase;
use Tests\Iterators\JsonDataProviderIterator;

class OrderByRatingDescStrategyTest extends TestCase
{
    private RoleFactory $roleFactory;
    private OrderByRatingDescStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleFactory = new RoleFactory();
        $this->strategy = new OrderByRatingDescStrategy();
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testReturnOrderedStrategy($data)
    {
//        $expectedOrderedPlayers = $this->collectionFromData($data['expectedOrderedPlayers']);
        $orderedCollection = $this->strategy->execute($this->collectionFromData($data['players']));
        $this->assertEquals(
            json_encode($data['expectedOrderedPlayers']),
            json_encode($orderedCollection->toArray())
        );
    }

    public function playerCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'default');
    }

    private function collectionFromData($data): PlayerCollection
    {
        $playerCollection = new PlayerCollection();
        foreach ($data as $playersArray) {
            $playerCollection->add(
                new Player(
                    $playersArray['number'],
                    $playersArray['name'],
                    $this->roleFactory->build($playersArray['role']),
                    new CoachRating($playersArray['rating'])
                )
            );
        }

        return $playerCollection;
    }
}
