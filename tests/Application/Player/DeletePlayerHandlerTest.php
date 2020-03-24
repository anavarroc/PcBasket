<?php

namespace Tests\Application\Player;

use PcBasket\Application\Player\DeletePlayerCommand;
use PcBasket\Application\Player\DeletePlayerHandler;
use PcBasket\Domain\Player\CoachRating;
use PcBasket\Domain\Player\Event\PlayerDeleted;
use PcBasket\Domain\Player\Exception\PlayerNotFoundException;
use PcBasket\Domain\Player\Player;
use PcBasket\Domain\Role\Center;
use PcBasket\Infrastructure\Persistence\Player\MemoryPlayerRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

class DeletePlayerHandlerTest extends TestCase
{
    private MemoryPlayerRepository $playerRepository;
    private DeletePlayerHandler $handler;
    private EventDispatcher $eventDispatcher;
    private TraceableEventDispatcher $traceableEventDispatcher;

    public function setUp(): void
    {
        parent::setUp();
        $this->traceableEventDispatcher = new TraceableEventDispatcher(
            new EventDispatcher(),
            new Stopwatch()
        );
        $this->playerRepository = new MemoryPlayerRepository();
        $this->handler = new DeletePlayerHandler(
            $this->playerRepository,
            $this->traceableEventDispatcher
        );
    }

    public function testShouldDeletePlayer()
    {
        $player = $this->createAndSavePlayer();
        $this->handler->handle(
            new DeletePlayerCommand(
                $player->getNumber()
            )
        );
        $deletedPlayer = $this->playerRepository->find($player->getNumber());
        $this->assertNull($deletedPlayer);
    }

    public function testShouldNotify()
    {
        $player = $this->createAndSavePlayer();
        $this->handler->handle(
            new DeletePlayerCommand(
                $player->getNumber()
            )
        );
        $notifiedEvents = $this->traceableEventDispatcher->getOrphanedEvents();
        $this->assertCount(1, $notifiedEvents);
        $this->assertEquals(PlayerDeleted::class, $notifiedEvents[0]);
    }

    public function testShouldThrowException()
    {
        $this->expectException(PlayerNotFoundException::class);
        $this->handler->handle(
            new DeletePlayerCommand(
                rand(1, 100)
            )
        );
    }

    private function createAndSavePlayer(): Player
    {
        $player = new Player(
            rand(1, 100),
            uniqid(),
            new Center(),
            new CoachRating(rand(1, 10))
        );
        $this->playerRepository->save($player);

        return $player;
    }
}
