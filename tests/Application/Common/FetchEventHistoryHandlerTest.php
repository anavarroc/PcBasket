<?php

namespace Tests\Application\Common;

use DateTime;
use PcBasket\Application\Common\FetchEventHistoryHandler;
use PcBasket\Application\Common\FetchEventHistoryQuery;
use PcBasket\Domain\common\ReadEvent;
use PcBasket\Infrastructure\Persistence\Event\MemoryReadEventRepository;
use PHPUnit\Framework\TestCase;
use Tests\Iterators\JsonDataProviderIterator;

class FetchEventHistoryHandlerTest extends TestCase
{
    private MemoryReadEventRepository $eventRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventRepository = new MemoryReadEventRepository();
    }

    /**
     * @dataProvider eventCollectionData
     */
    public function testShouldFetchAllEvent($data)
    {
        $this->saveEventsFromData($data['events']);
        $handler = new FetchEventHistoryHandler(
            $this->eventRepository
        );
        $eventCollection = $handler->handle(new FetchEventHistoryQuery());
        $this->assertCount(count($data['events']), $eventCollection);
    }

    public function eventCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'default');
    }

    private function saveEventsFromData($data)
    {
        foreach ($data as $eventArray) {
            $this->eventRepository->save(
                new ReadEvent(
                    $eventArray['item'],
                    new DateTime($eventArray['date']),
                    $eventArray['name']
                )
            );
        }
    }
}
