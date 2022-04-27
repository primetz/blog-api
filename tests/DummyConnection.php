<?php

namespace Tests;

use App\Drivers\ConnectionInterface;
use PDO;
use PDOStatement;

class DummyConnection implements ConnectionInterface
{
    private PDO $connection;

    private PDOStatement $PDOStatementMock;

    public function __construct(
        PDO $connection,
        PDOStatement $PDOStatementMock
    )
    {
        $this->connection = $connection;

        $this->PDOStatementMock = $PDOStatementMock;
    }

    public function prepare(string $query): PDOStatement
    {
        $this->connection->prepare($query);

        return $this->PDOStatementMock;
    }

    public function execute(array $params)
    {
        $this->PDOStatementMock->execute($params);
    }

    public function executeQuery(string $query, array $params): void
    {
        $this->prepare($query)->execute($params);
    }

    public function lastInsertId($name = null): bool|string
    {
        return $this->connection->lastInsertId($name);
    }
}
