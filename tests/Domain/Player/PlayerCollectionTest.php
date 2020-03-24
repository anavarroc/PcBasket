<?php

namespace Tests\Domain\Player;

use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Role\Center;
use PcBasket\Domain\Role\PointGuard;
use PcBasket\Domain\Role\PowerForward;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Domain\Role\ShootingGuard;
use PcBasket\Domain\Role\SmallForward;
use PHPUnit\Framework\TestCase;
use Tests\Iterators\JsonDataProviderIterator;

class PlayerCollectionTest extends TestCase
{
    private RoleFactory $roleFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleFactory = new RoleFactory();
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testCollectionFilterPointGuards($data)
    {
        $playersCollection = $this->collectionFromData($data['players']);
        $filteredCollection = $playersCollection->filterByRole(new PointGuard());
        $this->assertCount($data['pointGuards'], $filteredCollection);
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testCollectionFilterShootingGuards($data)
    {
        $playersCollection = $this->collectionFromData($data['players']);
        $filteredCollection = $playersCollection->filterByRole(new ShootingGuard());
        $this->assertCount($data['shootingGuards'], $filteredCollection);
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testCollectionFilterSmallForward($data)
    {
        $playersCollection = $this->collectionFromData($data['players']);
        $filteredCollection = $playersCollection->filterByRole(new SmallForward());
        $this->assertCount($data['smallForwards'], $filteredCollection);
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testCollectionFilterCenter($data)
    {
        $playersCollection = $this->collectionFromData($data['players']);
        $filteredCollection = $playersCollection->filterByRole(new Center());
        $this->assertCount($data['centers'], $filteredCollection);
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testCollectionFilterPowerForwards($data)
    {
        $playersCollection = $this->collectionFromData($data['players']);
        $filteredCollection = $playersCollection->filterByRole(new PowerForward());
        $this->assertCount($data['powerForwards'], $filteredCollection);
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
