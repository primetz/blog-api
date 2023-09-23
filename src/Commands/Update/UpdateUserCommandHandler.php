<?php

namespace App\Commands\Update;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Entities\User\User;
use App\Repositories\UserRepository\UserRepositoryInterface;

class UpdateUserCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        ConnectionInterface $connection,
        private readonly UserRepositoryInterface $userRepository
    )
    {
        parent::__construct($connection);
    }

    /**
     * @param UpdateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->userRepository->get($command->getEntity()->getId());

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    public function getSql(): string
    {
        return 'UPDATE users SET
                    first_name = :firstName,
                    last_name = :lastName,
                    email = :email,
                    password = :password
                WHERE id = :id';
    }

    /**
     * @param UpdateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var User $user
         */
        $user = $command->getEntity();

        return [
            ':id' => $user->getId(),
            ':firstName' => $user->getFirstName(),
            ':lastName' => $user->getLastName(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
        ];
    }
}
