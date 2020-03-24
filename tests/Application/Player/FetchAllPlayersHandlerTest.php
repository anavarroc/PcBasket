<?php

namespace Tests\Application\Player;

use PcBasket\Application\Player\FetchAllPlayersHandler;
use PcBasket\Application\Player\FetchAllPlayersQuery;
use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\OrderByNumberAscStrategy;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Infrastructure\Persistence\Player\MemoryPlayerRepository;
use PHPUnit\Framework\TestCase;
use Tests\Iterators\JsonDataProviderIterator;

class FetchAllPlayersHandlerTest extends TestCase
{
    private MemoryPlayerRepository $playerRepository;
    private RoleFactory $roleFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->playerRepository = new MemoryPlayerRepository();
        $this->roleFactory = new RoleFactory();
    }

    /**
     * @dataProvider playerCollectionData
     */
    public function testShouldFetchAllPlayers($data)
    {
        $this->savePlayersFromData($data['players']);
        $handler = new FetchAllPlayersHandler(
            $this->playerRepository
        );
        $playerCollection = $handler->handle(new FetchAllPlayersQuery());
        $this->assertCount(count($data['players']), $playerCollection);
    }

    public function testShouldCallOrderStrategyOnce()
    {
        $strategy = $this->getMockBuilder(OrderByNumberAscStrategy::class)
            ->onlyMethods(['execute'])
            ->getMock();
        $strategy->expects($this->once())
            ->method('execute');

        $handler = new FetchAllPlayersHandler(
            $this->playerRepository,
            $strategy
        );
        $handler->handle(new FetchAllPlayersQuery());
    }

    public function playerCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'default');
    }

    private function savePlayersFromData($data)
    {
        foreach ($data as $playersArray) {
            $this->playerRepository->save(
                new Player(
                    $playersArray['number'],
                    $playersArray['name'],
                    $this->roleFactory->build($playersArray['role']),
                    new CoachRating($playersArray['rating'])
                )
            );
        }
    }
}
