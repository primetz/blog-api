<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;

abstract class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var PdoConnectionDriver $connection
     */
    protected ConnectionInterface $connection;

    private ?ConnectorInterface $connector;

    public function __construct(
        ?ConnectorInterface $connector = null,
    )
    {
        $this->connector = $connector ?? new SqlLiteConnector();

        $this->connection = $this->connector->getConnection();
    }

    public function getLastInsertId(?string $name = null): int|false
    {
        return $this->connection->lastInsertId($name) ? (int) $this->connection->lastInsertId($name) : false;
    }

    abstract public function handle(CommandInterface $command): void;

    abstract public function getSql(): string;

    abstract public function getParams(CommandInterface $command): array;
}
