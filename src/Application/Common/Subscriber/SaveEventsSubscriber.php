<?php

namespace PcBasket\Application\Common\Subscriber;

use PcBasket\Domain\common\WriteEventRepositoryInterface;
use PcBasket\Domain\Player\Event\PlayerCreated;
use PcBasket\Domain\Player\Event\PlayerDeleted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SaveEventsSubscriber implements EventSubscriberInterface
{
    private WriteEventRepositoryInterface $eventRepository;

    public function __construct(
        WriteEventRepositoryInterface $eventRepository
    ) {
        $this->eventRepository = $eventRepository;
    }

    public function onWriteEvent($event)
    {
        $this->eventRepository->save($event);
    }

    public static function getSubscribedEvents()
    {
        return [
            PlayerCreated::class => 'onWriteEvent',
            PlayerDeleted::class => 'onWriteEvent',
        ];
    }
}
