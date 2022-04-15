<?php

namespace App\Queries;

use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;

abstract class QueryHandler implements QueryHandlerInterface
{

    public function __construct(
        protected ConnectionInterface $connection
    )
    {
    }

    abstract public function handle(QueryInterface $query): EntityInterface;

    abstract public function getSql(): string;

    abstract public function getParams(QueryInterface $query): array;
}
