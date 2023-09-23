<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Entities\User\UserInterface;
use App\Exceptions\UserEmailExistsException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepositoryInterface;

final class CreateUserCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        ConnectionInterface $connection,
        private readonly UserRepositoryInterface $userRepository
    )
    {
        parent::__construct($connection);
    }

    /**
     * @throws UserEmailExistsException
     * @throws UserNotFoundException
     */
    public function handle(CommandInterface $command): EntityInterface
    {
        /**
         * @var CreateEntityCommand $command
         * @var User $user
         */
        $user = $command->getEntity();

        $email = $user->getEmail();

        if ($this->isUserExists($email)) {
            throw new UserEmailExistsException(
                sprintf('User with email %s already exists', $email)
            );
        }

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );

        return $user->getId() ? $user : $this->userRepository->getByEmail($email);
    }

    private function isUserExists(string $email): bool
    {
        try {
            $this->userRepository->getByEmail($email);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }

    public function getSql(): string
    {
        return 'INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)';
    }

    /**
     * @param CreateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var UserInterface $user
         */
        $user = $command->getEntity();

        return [
            ':firstName' => $user->getFirstName(),
            ':lastName' => $user->getLastName(),
            ':email' => $user->getEmail(),
            ':password' => $user->hashPassword($user->getPassword()),
        ];
    }
}
