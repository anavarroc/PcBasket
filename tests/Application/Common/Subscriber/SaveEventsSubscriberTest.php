<?php

namespace Tests\Application\Common\Subscriber;

use PcBasket\Application\Common\Subscriber\SaveEventsSubscriber;
use PcBasket\Domain\Player\Event\PlayerCreated;
use PcBasket\Infrastructure\Persistence\Event\MemoryWriteEventRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

class SaveEventsSubscriberTest extends TestCase
{
    private TraceableEventDispatcher $eventDispatcher;
    private MemoryWriteEventRepository $eventRepository;
    private SaveEventsSubscriber $subscriber;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventDispatcher = new TraceableEventDispatcher(
            new EventDispatcher(),
            new Stopwatch()
        );
        $this->eventRepository = new MemoryWriteEventRepository();
        $this->subscriber = new SaveEventsSubscriber(
            $this->eventRepository
        );
        $this->eventDispatcher->addSubscriber($this->subscriber);
    }

    public function testSubscriberShouldBeCalled()
    {
        $this->eventDispatcher->dispatch(new PlayerCreated(uniqid()));
        $this->assertCount(0, $this->eventDispatcher->getOrphanedEvents());
        $calledListeners = $this->eventDispatcher->getCalledListeners();
        $this->assertCount(1, $calledListeners);
        $this->assertEquals(
            SaveEventsSubscriber::class
            . '::'
            . SaveEventsSubscriber::getSubscribedEvents()[PlayerCreated::class],
            $calledListeners[0]['pretty']);
    }

    public function testSubscriberShouldSaveEvent()
    {
        $this->eventDispatcher->dispatch(new PlayerCreated(uniqid()));
        $events = $this->eventRepository->findAll();
        $this->assertCount(1, $events);
        $this->assertTrue($events[0] instanceof PlayerCreated);
    }
}
