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

        return $this->getUser($statement);
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

        return $this->getUser($statement);
    }

    /**
     * @param User $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connection
            ->prepare(
                'INSERT INTO users (first_name, last_name, email) VALUES (:first_name, :last_name, :email)'
            );

        $statement->execute([
            ':first_name' => $entity->getFirstName(),
            ':last_name' => $entity->getLastName(),
            ':email' => $entity->getEmail()
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement): UserInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new UserNotFoundException('Can\'t find user');
        }

        return new User(
            $result['first_name'],
            $result['last_name'],
            $result['email'],
            $result['id']
        );
    }
}
