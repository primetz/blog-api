<?php

namespace App\Queries;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;

abstract class QueryHandler implements QueryHandlerInterface
{
    protected ConnectionInterface $connection;

    private ConnectorInterface $connector;

    public function __construct(
        ?ConnectorInterface $connector = null
    )
    {
        $this->connector = $connector ?? new SqlLiteConnector();

        $this->connection = $this->connector->getConnection();
    }

    abstract public function handle(QueryInterface $query): EntityInterface;

    abstract public function getSql(): string;

    abstract public function getParams(QueryInterface $query): array;
}
