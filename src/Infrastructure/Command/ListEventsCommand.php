<?php

namespace PcBasket\Infrastructure\Command;

use PcBasket\Application\Common\EventDtoCollection;
use PcBasket\Application\Common\FetchEventHistoryHandler;
use PcBasket\Application\Common\FetchEventHistoryQuery;
use PcBasket\Domain\Player\PlayerCollectionOrderStrategyInterface;
use PcBasket\Infrastructure\Persistence\Event\JsonReadEventRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListEventsCommand extends Command
{
    protected static $defaultName = 'app:list-events';

    protected function configure()
    {
        $this->setDescription('Lists events')
            ->setHelp('This command shows you a historic of operations over players');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '============',
            'List Events',
            '============',
            '',
        ]);
        $eventList = $this->listEvents();
        $this->printEvents($eventList, $output);

        return 0;
    }

    private function listEvents()
    {
        $handler = $this->getHandler();

        return $handler->handle(new FetchEventHistoryQuery());
    }

    private function getHandler(PlayerCollectionOrderStrategyInterface $orderStrategy = null): FetchEventHistoryHandler
    {
        return new FetchEventHistoryHandler(new JsonReadEventRepository());
    }

    private function printEvents(EventDtoCollection $eventDto, $output)
    {
        foreach ($eventDto as $dto) {
            $output->writeln(json_encode($dto->toArray()));
        }
    }
}
