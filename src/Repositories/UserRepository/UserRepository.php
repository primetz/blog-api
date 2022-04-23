<?php

namespace App\Repositories\UserRepository;

use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Entities\User\UserInterface;
use App\Exceptions\UserNotFoundException;
use App\Repositories\EntityRepository;
use PDO;
use PDOStatement;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): EntityInterface
    {
        $statement = $this->connection
            ->prepare(
                'SELECT * FROM users WHERE id = :id'
            );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getUser(
            $statement,
            [
                'id' => $id
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): UserInterface
    {
        $statement = $this->connection
            ->prepare(
                'SELECT * FROM users WHERE email = :email'
            );

        $statement->execute([
            ':email' => $email
        ]);

        return $this->getUser(
            $statement,
            [
                'email' => $email
            ]
        );
    }

    /**
     * @param User $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connection
            ->prepare(
                'INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)'
            );

        $statement->execute([
            ':first_name' => $entity->getFirstName(),
            ':last_name' => $entity->getLastName(),
            ':email' => $entity->getEmail(),
            ':password_hash' => $entity->getPassword()
        ]);

        $this->logger->info(
            'User created',
            [
                'userId' => $this->connection->lastInsertId(),
                'class' => self::class,
                'method' => __METHOD__
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement, array $params): UserInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $this->logger->warning(
                'Failed to find user',
                [
                    'params' => $params,
                    'class' => self::class,
                    'method' => __METHOD__,
                    'caller_method' => $backtrace['class'] . '::' . $backtrace['function']
                ]
            );

            throw new UserNotFoundException('Can\'t find user');
        }

        return new User(
            $result['first_name'],
            $result['last_name'],
            $result['email'],
            $result['password'],
            $result['id']
        );
    }
}
