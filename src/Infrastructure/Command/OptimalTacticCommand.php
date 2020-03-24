<?php

namespace PcBasket\Infrastructure\Command;

use PcBasket\Application\Tactic\GetOptimizedTacticHandler;
use PcBasket\Application\Tactic\GetOptimizedTacticQuery;
use PcBasket\Application\Tactic\TacticDto;
use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Domain\Tactic\TacticCalculator\OneThreeOneDefenceTacticCalculator;
use PcBasket\Domain\Tactic\TacticCalculator\TacticCalculatorService;
use PcBasket\Domain\Tactic\TacticCalculator\TwoThreeZoneDefenceTacticCalculator;
use PcBasket\Domain\Tactic\TacticCalculator\TwoTwoOneAttackTacticCalculator;
use PcBasket\Infrastructure\Persistence\Player\JsonPlayerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class OptimalTacticCommand extends Command
{
    const TACTIC_QUESTION = 'Elija alineamiento a optimizar:';
    protected static $defaultName = 'app:optimize-tactic';

    protected function configure()
    {
        $this->setDescription('Lists players')
            ->setHelp('This command shows you a list of all players');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Optimize tactic',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');

        try {
            $tactic = $this->getOptimizedTactic($input, $output, $helper);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return 0;
        }
        $this->printTactic($tactic, $output);

        return 0;
    }

    protected function getOptimizedTactic(InputInterface $input, OutputInterface $output, $helper): TacticDto
    {
        $tactic = null;
        switch ($this->askTactic($input, $output, $helper)) {
            case OneThreeOneDefenceTacticCalculator::NAME:
                $tactic = $this->optimizePlayersForOneThreeOneDefence();
                break;
            case TwoThreeZoneDefenceTacticCalculator::NAME:
                $tactic = $this->optimizePlayersForTwoThreeZonDefence();
                break;
            case TwoTwoOneAttackTacticCalculator::NAME:
                $tactic = $this->optimizePlayersForTwoTwoOneAttack();
                break;
        }

        return $tactic;
    }

    private function optimizePlayersForOneThreeOneDefence()
    {
        return $this->executeHandler(
            new OneThreeOneDefenceTacticCalculator(
                new OrderByRatingDescStrategy()
            )
        );
    }

    private function optimizePlayersForTwoThreeZonDefence()
    {
        return $this->executeHandler(
            new TwoThreeZoneDefenceTacticCalculator(
                new OrderByRatingDescStrategy()
            )
        );
    }

    private function optimizePlayersForTwoTwoOneAttack()
    {
        return $this->executeHandler(
            new TwoTwoOneAttackTacticCalculator(
                new OrderByRatingDescStrategy()
            )
        );
    }

    private function executeHandler(TacticCalculatorService $tacticCalculator): TacticDto
    {
        $handler = new GetOptimizedTacticHandler(
            new JsonPlayerRepository(
                new RoleFactory()
            ),
            $tacticCalculator
        );

        return $handler->handle(new GetOptimizedTacticQuery());
    }

    private function askTactic(InputInterface $input, OutputInterface $output, $helper)
    {
        $question = new ChoiceQuestion(self::TACTIC_QUESTION,
            [
                OneThreeOneDefenceTacticCalculator::NAME,
                TwoThreeZoneDefenceTacticCalculator::NAME,
                TwoTwoOneAttackTacticCalculator::NAME,
            ]
        );

        return $helper->ask($input, $output, $question);
    }

    private function printTactic(TacticDto $tactic, $output)
    {
        $array = $tactic->toArray();
        $output->writeln(json_encode($array['name']));
        foreach ($array['players'] as $player) {
            $output->writeln(json_encode($player));
        }
    }
}
