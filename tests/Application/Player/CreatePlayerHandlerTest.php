<?php

namespace Tests\Application\Player;

use PcBasket\Application\Player\CreatePlayerCommand;
use PcBasket\Application\Player\CreatePlayerHandler;
use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Event\PlayerCreated;
use PcBasket\Domain\Player\Exception\PlayerAlreadyExistsException;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Role\Center;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Infrastructure\Persistence\Player\MemoryPlayerRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

class CreatePlayerHandlerTest extends TestCase
{
    private MemoryPlayerRepository $playerRepository;
    private CreatePlayerHandler $handler;
    private TraceableEventDispatcher $traceableEventDispatcher;

    public function setUp(): void
    {
        parent::setUp();
        $this->traceableEventDispatcher = new TraceableEventDispatcher(
            new EventDispatcher(),
            new Stopwatch()
        );
        $this->playerRepository = new MemoryPlayerRepository();
        $this->handler = new CreatePlayerHandler(
            $this->playerRepository,
            new RoleFactory(),
            $this->traceableEventDispatcher
        );
    }

    public function testShouldCreatePlayer()
    {
        $playerNumber = rand(1, 100);
        $this->handler->handle(
            new CreatePlayerCommand(
                $playerNumber,
                uniqid(),
                new Center(),
                rand(1, 10)
            )
        );

        $player = $this->playerRepository->find($playerNumber);
        $this->assertNotNull($player);
        $this->assertTrue($player instanceof Player);
    }

    public function testShouldNotify()
    {
        $this->handler->handle(
            new CreatePlayerCommand(
                rand(1, 100),
                uniqid(),
                new Center(),
                rand(1, 10)
            )
        );
        $notifiedEvents = $this->traceableEventDispatcher->getOrphanedEvents();
        $this->assertCount(1, $notifiedEvents);
        $this->assertEquals(PlayerCreated::class, $notifiedEvents[0]);
    }

    public function testShouldThrowException()
    {
        $this->expectException(PlayerAlreadyExistsException::class);
        $player = new Player(
            rand(1, 100),
            uniqid(),
            new Center(),
            new CoachRating(rand(1, 10))
        );
        $this->playerRepository->save($player);
        $this->handler->handle(
            new CreatePlayerCommand(
                $player->getNumber(),
                $player->getName(),
                $player->getRole(),
                $player->getCoachRating()->getRating()
            )
        );
    }
}
