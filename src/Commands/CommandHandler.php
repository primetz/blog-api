<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;

abstract class CommandHandler implements CommandHandlerInterface
{
    protected ConnectionInterface $connection;

    private ?ConnectorInterface $connector;

    public function __construct(
        ?ConnectorInterface $connector = null,
    )
    {
        $this->connector = $connector ?? new SqlLiteConnector();

        $this->connection = $this->connector->getConnection();
    }

    abstract public function handle(CommandInterface $command): void;

    abstract public function getSql(): string;

    abstract public function getParams(EntityInterface $entity): array;
}
