<?php

namespace App\Commands\SymfonyCommands\Update;

use App\Commands\Update\UpdateEntityCommand;
use App\Commands\Update\UpdateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
{
    public function __construct(
        private readonly UpdateUserCommandHandler $updateUserCommandHandler,
        private UserRepositoryInterface $userRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('update:user')
            ->setDescription('Update a user')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of a user to update')
            ->addOption(
                'first-name',
                'f',
                InputArgument::OPTIONAL,
                'First name'
            )
            ->addOption(
                'last-name',
                'l',
                InputArgument::OPTIONAL,
                'Last name'
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $firstName = $input->getOption('first-name');

        $lastName = $input->getOption('last-name');

        if (empty($firstName) && empty($lastName)) {
            $output->writeln('Nothing to update');

            return Command::SUCCESS;
        }

        try {
            /**
             * @var User $user
             */
            $user = $this->userRepository->get($input->getArgument('id'));

            $firstName = $input->getOption('first-name') ?? $user->getFirstName();

            $lastName = $input->getOption('last-name') ?? $user->getLastName();

            $user
                ->setFirstName($firstName)
                ->setLastName($lastName);

            $this->updateUserCommandHandler->handle(new UpdateEntityCommand($user));
        } catch (UserNotFoundException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln(
            sprintf('User updated: %d', $user->getId())
        );

        return Command::SUCCESS;
    }
}
