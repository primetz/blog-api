<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    public function __construct(
        protected ?ConnectionInterface $connection = null,
    )
    {
    }

    abstract public function get(int $id): EntityInterface;

    abstract public function save(EntityInterface $entity): void;
}
