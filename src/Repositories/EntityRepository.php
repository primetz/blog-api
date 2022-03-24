<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    protected ConnectionInterface $connection;

    public function __construct(
        ConnectorInterface $connector = null,
    )
    {
        $connector = $connector ?? new SqlLiteConnector();

        $this->connection = $connector->getConnection();
    }

    abstract public function get(int $id): EntityInterface;

    abstract public function save(EntityInterface $entity): void;
}
