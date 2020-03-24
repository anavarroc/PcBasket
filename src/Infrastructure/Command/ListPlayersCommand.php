<?php

namespace PcBasket\Infrastructure\Command;

use PcBasket\Application\Player\FetchAllPlayersHandler;
use PcBasket\Application\Player\FetchAllPlayersQuery;
use PcBasket\Application\Player\PlayerDtoCollection;
use PcBasket\Domain\Player\OrderByNumberAscStrategy;
use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Player\PlayerCollectionOrderStrategyInterface;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Infrastructure\Persistence\Player\JsonPlayerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ListPlayersCommand extends Command
{
    const NO_ORDER = 'Sin ordenar';
    const RATING_DESC = 'Valoración Desc';
    const NUMBER_ASC = 'Número de Dorsal Asc';
    const ORDER_QUESTION = 'Elija orden:';

    protected static $defaultName = 'app:list-players';

    protected function configure()
    {
        $this->setDescription('Lists players')
            ->setHelp('This command shows you a list of all players');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '============',
            'List Players',
            '============',
        ]);

        $helper = $this->getHelper('question');
        $playerList = null;
        $optionSelected = $this->askListOrder($input, $output, $helper);

        switch ($optionSelected) {
            case self::NO_ORDER:
                $playerList = $this->listPlayers();
                break;
            case self::RATING_DESC:
                $playerList = $this->listPlayersOrderByRating();
                break;
            case self::NUMBER_ASC:
                $playerList = $this->listPlayersOrderByNumber();
                break;
        }

        $this->printPlayers($playerList, $output);

        return 0;
    }

    private function listPlayers()
    {
        $handler = $this->getHandler();

        return $handler->handle(new FetchAllPlayersQuery());
    }

    private function listPlayersOrderByRating()
    {
        $orderStrategy = new OrderByRatingDescStrategy();
        $handler = $this->getHandler($orderStrategy);

        return $handler->handle(new FetchAllPlayersQuery());
    }

    private function listPlayersOrderByNumber()
    {
        $orderStrategy = new OrderByNumberAscStrategy();
        $handler = $this->getHandler($orderStrategy);

        return $handler->handle(new FetchAllPlayersQuery());
    }

    private function getHandler(PlayerCollectionOrderStrategyInterface $orderStrategy = null): FetchAllPlayersHandler
    {
        return new FetchAllPlayersHandler(
            new JsonPlayerRepository(
                new RoleFactory()
            ),
            $orderStrategy
        );
    }

    private function askListOrder(InputInterface $input, OutputInterface $output, $helper)
    {
        $question = new ChoiceQuestion(self::ORDER_QUESTION,
            [
                self::NO_ORDER,
                self::RATING_DESC,
                self::NUMBER_ASC,
            ]
        );

        return $helper->ask($input, $output, $question);
    }

    private function printPlayers(PlayerDtoCollection $playerList, $output)
    {
        foreach ($playerList as $dto) {
            $output->writeln(json_encode($dto->toArray()));
        }
    }
}
