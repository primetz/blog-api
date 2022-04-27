<?php

namespace App\Commands\SymfonyCommands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private readonly CreateUserCommandHandler $createUserCommandHandler,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('create:user')
            ->setDescription('Creates new user')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $output->writeln('Create user command started');

        $user = new User(
            $input->getArgument('firstName'),
            $input->getArgument('lastName'),
            $input->getArgument('email'),
            $input->getArgument('password')
        );

        try {
            $this->createUserCommandHandler->handle(new CreateEntityCommand($user));
        } catch (UserEmailExistsException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln(
            sprintf('User created: %s', $user->getEmail())
        );

        return Command::SUCCESS;
    }
}
