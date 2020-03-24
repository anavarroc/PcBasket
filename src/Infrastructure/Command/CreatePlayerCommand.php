<?php

namespace PcBasket\Infrastructure\Command;

use PcBasket\Application\Player\CreatePlayerCommand as CreatePlayerHandlerCommand;
use PcBasket\Application\Player\CreatePlayerHandler;
use PcBasket\Domain\Role\Center;
use PcBasket\Domain\Role\PointGuard;
use PcBasket\Domain\Role\PowerForward;
use PcBasket\Domain\Role\RoleFactory;
use PcBasket\Domain\Role\ShootingGuard;
use PcBasket\Domain\Role\SmallForward;
use PcBasket\Infrastructure\Persistence\Player\JsonPlayerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreatePlayerCommand extends Command
{
    protected static $defaultName = 'app:create-player';

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this->setDescription('Creates a new player.')
            ->setHelp('This command allows you to create a player.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '=============',
            'Create Player',
            '=============',
        ]);
        $helper = $this->getHelper('question');
        try {
            $this->createPlayer(
                $this->askPlayerNumber($input, $output, $helper),
                $this->askPlayerName($input, $output, $helper),
                $this->askPlayerRole($input, $output, $helper),
                $this->askPlayerRating($input, $output, $helper)
            );
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return 0;
        }
        $output->writeln('Player created.');

        return 0;
    }

    private function createPlayer(
        int $number,
        string $name,
        string $role,
        int $coachRating
    ) {
        $handler = $this->getHandler();
        $handler->handle(
            new CreatePlayerHandlerCommand(
                $number,
                $name,
                $role,
                $coachRating
            )
        );
    }

    private function getHandler(): CreatePlayerHandler
    {
        $roleFactory = new RoleFactory();

        return new CreatePlayerHandler(
            new JsonPlayerRepository($roleFactory),
            $roleFactory,
            $this->eventDispatcher
        );
    }

    private function askPlayerNumber(InputInterface $input, OutputInterface $output, $helper): int
    {
        $question = new Question("Introduzca el número de dorsal:\n", false);

        return (int) $helper->ask($input, $output, $question);
    }

    protected function askPlayerName(InputInterface $input, OutputInterface $output, $helper): string
    {
        $question = new Question("Introduzca el nombre:\n", false);

        return $helper->ask($input, $output, $question);
    }

    private function askPlayerRole(InputInterface $input, OutputInterface $output, $helper)
    {
        $question = new ChoiceQuestion('Elija una posición:',
            [
                Center::NAME,
                PowerForward::NAME,
                PointGuard::NAME,
                ShootingGuard::NAME,
                SmallForward::NAME,
            ]
        );

        return $helper->ask($input, $output, $question);
    }

    private function askPlayerRating(InputInterface $input, OutputInterface $output, $helper)
    {
        $question = new Question("Enter Player Rating:\n", false);

        return $helper->ask($input, $output, $question);
    }
}
