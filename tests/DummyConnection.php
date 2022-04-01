<?php

namespace Tests;

use App\Drivers\ConnectionInterface;
use PDO;
use PDOStatement;

class DummyConnection implements ConnectionInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function prepare(string $query): bool|PDOStatement
    {
        return $this->connection->prepare($query);
    }

    public function executeQuery(string $query, array $params): void
    {
        $this->connection->prepare($query)->execute($params);
    }
}
