<?php

namespace PcBasket\Infrastructure\Command;

use PcBasket\Application\Player\DeletePlayerCommand as DeletePlayerHandlerCommand;
use PcBasket\Application\Player\DeletePlayerHandler;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Infrastructure\Persistence\Player\JsonPlayerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeletePlayerCommand extends Command
{
    protected static $defaultName = 'app:delete-player';

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this->setDescription('Delete a player.')
            ->setHelp('This command allows you to delete a player by number.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Delete Player',
            '============',
        ]);
        $helper = $this->getHelper('question');
        $playerNumber = $this->askPlayerNumber($input, $output, $helper);

        if (!$this->askConfirmation($input, $output, $playerNumber, $helper)) {
            return 0;
        }

        try {
            $this->deletePlayer($playerNumber);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return 0;
        }

        $output->write('Player Deleted.');

        return 0;
    }

    private function deletePlayer(int $playerNumber)
    {
        $handler = $this->getHandler();
        $handler->handle(new DeletePlayerHandlerCommand($playerNumber));
    }

    private function getHandler(): DeletePlayerHandler
    {
        return new DeletePlayerHandler(
            new JsonPlayerRepository(new RoleFactory()),
            $this->eventDispatcher
        );
    }

    private function askPlayerNumber(InputInterface $input, OutputInterface $output, $helper): int
    {
        $question = new Question("Introduzca el número de dorsal:\n", false);

        return (int) $helper->ask($input, $output, $question);
    }

    protected function askConfirmation(InputInterface $input, OutputInterface $output, int $playerNumber, $helper): bool
    {
        $question = new ConfirmationQuestion('Se eliminará el jugador con el dorsal ' . $playerNumber . ',¿Continuar?', false);

        return $helper->ask($input, $output, $question);
    }
}
