<?php

namespace Tests\Domain\Tactic\TacticCalculator;

use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Exception\NotEnoughPlayersForGivenRoleException;
use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Domain\Tactic\TacticCalculator\OneThreeOneDefenceTacticCalculator;
use PHPUnit\Framework\TestCase;
use Tests\Iterators\JsonDataProviderIterator;

class OneThreeOneDefenceStrategyTest extends TestCase
{
    private RoleFactory $roleFactory;
    private OneThreeOneDefenceTacticCalculator $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleFactory = new RoleFactory();
        $this->strategy = new OneThreeOneDefenceTacticCalculator(
            new OrderByRatingDescStrategy()
        );
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testReturnOptimizedTactic($data)
    {
        $expectedTacticPlayers = $this->collectionFromData($data['expectedTactic']);
        $tactic = $this->strategy->execute($this->collectionFromData($data['players']));
        $this->assertCount(5, $tactic->getPlayers());
        $this->assertEquals(
            json_encode($expectedTacticPlayers->toArray()),
            json_encode($tactic->getPlayers()->toArray())
        );
    }

    /**
     * @dataProvider notEnoughPointGuardData
     */
    public function testShouldThrowsNotEnoughPointGuards($data)
    {
        try {
            $this->strategy->execute($this->collectionFromData($data['players']));
        } catch (NotEnoughPlayersForGivenRoleException $e) {
            $this->assertEquals(
                $data['expectedExceptionMsg'],
                $e->getMessage()
            );
        }
    }

    /**
     * @dataProvider notEnoughPowerForwardData
     */
    public function testShouldThrowsNotEnoughPowerForwards($data)
    {
        try {
            $this->strategy->execute($this->collectionFromData($data['players']));
        } catch (NotEnoughPlayersForGivenRoleException $e) {
            $this->assertEquals(
                $data['expectedExceptionMsg'],
                $e->getMessage()
            );
        }
    }

    /**
     * @dataProvider notEnoughShootingGuardData
     */
    public function testShouldThrowsNotEnoughShootingGuards($data)
    {
        try {
            $this->strategy->execute($this->collectionFromData($data['players']));
        } catch (NotEnoughPlayersForGivenRoleException $e) {
            $this->assertEquals(
                $data['expectedExceptionMsg'],
                $e->getMessage()
            );
        }
    }

    /**
     * @dataProvider notEnoughCenterData
     */
    public function testShouldThrowsNotEnoughCenters($data)
    {
        try {
            $this->strategy->execute($this->collectionFromData($data['players']));
        } catch (NotEnoughPlayersForGivenRoleException $e) {
            $this->assertEquals(
                $data['expectedExceptionMsg'],
                $e->getMessage()
            );
        }
    }

    public function playerCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'Default');
    }

    public function notEnoughPointGuardData()
    {
        return new JsonDataProviderIterator(__FILE__, 'notEnoughPointGuard');
    }

    public function notEnoughShootingGuardData()
    {
        return new JsonDataProviderIterator(__FILE__, 'notEnoughShootingGuardData');
    }

    public function notEnoughPowerForwardData()
    {
        return new JsonDataProviderIterator(__FILE__, 'notEnoughPowerForwardData');
    }

    public function notEnoughCenterData()
    {
        return new JsonDataProviderIterator(__FILE__, 'notEnoughCenterData');
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
