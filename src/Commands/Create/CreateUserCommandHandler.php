<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Connections\ConnectorInterface;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;

final class CreateUserCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    private ?UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository = null,
        ConnectorInterface $connector = null
    )
    {
        $this->userRepository = $userRepository ?? new UserRepository();

        parent::__construct($connector);
    }

    /**
     * @throws UserEmailExistException
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var CreateEntityCommand $command
         * @var User $user
         */
        $user = $command->getEntity();

        $email = $user->getEmail();

        if ($this->isUserExists($email)) {
            throw new UserEmailExistException(
                sprintf('User with email %s already exists', $email)
            );
        }

        $this->statement->execute([
            ':firstName' => $user->getFirstName(),
            ':lastName' => $user->getLastName(),
            ':email' => $email,
        ]);
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

    protected function getSql(): string
    {
        return 'INSERT INTO users (first_name, last_name, email) VALUES (:firstName, :lastName, :email)';
    }
}
