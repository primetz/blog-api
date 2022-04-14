<?php

namespace App\Commands\Delete;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Connections\ConnectorInterface;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;

final class DeleteUserCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        ?UserRepositoryInterface $userRepository = null,
        ?ConnectorInterface $connector = null
    )
    {
        $this->userRepository = $userRepository ?? new UserRepository();

        parent::__construct($connector);
    }

    /**
     * @param DeleteEntityCommand $command
     * @throws UserNotFoundException
     */
    public function handle(CommandInterface $command): void
    {
        $this->userRepository->get($command->getId());

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    public function getSql(): string
    {
        return 'DELETE FROM users WHERE id = :id';
    }

    /**
     * @param DeleteEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        return [
            ':id' => $command->getId()
        ];
    }
}
