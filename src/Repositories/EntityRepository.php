<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    public function __construct(
        protected ConnectorInterface $connector,
    )
    {
    }

    abstract public function get(int $id): EntityInterface;

    abstract public function save(EntityInterface $entity): void;
}
