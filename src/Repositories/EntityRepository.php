<?php

namespace App\Repositories;

use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;
use App\Entities\EntityInterface;
use Psr\Log\LoggerInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    /**
     * @param PdoConnectionDriver $connection
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected ConnectionInterface $connection,
        protected LoggerInterface $logger,
    )
    {
    }

    abstract public function get(int $id): EntityInterface;

    abstract public function save(EntityInterface $entity): void;
}
