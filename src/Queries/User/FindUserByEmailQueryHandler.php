<?php

namespace App\Queries\User;

use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Entities\User\UserInterface;
use App\Exceptions\UserNotFoundException;
use App\Queries\QueryHandler;
use App\Queries\QueryHandlerInterface;
use App\Queries\QueryInterface;
use PDO;
use PDOStatement;

class FindUserByEmailQueryHandler extends QueryHandler implements QueryHandlerInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function handle(QueryInterface $query): EntityInterface
    {
        $statement = $this->connection->prepare(
            $this->getSql()
        );

        $statement->execute(
            $this->getParams($query)
        );

        return $this->getUser($statement);
    }

    public function getSql(): string
    {
        return 'SELECT * FROM users WHERE email = :email';
    }

    /**
     * @param FindUserByEmailQuery $query
     */
    public function getParams(QueryInterface $query): array
    {
        return [
            ':email' => $query->getEmail()
        ];
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
