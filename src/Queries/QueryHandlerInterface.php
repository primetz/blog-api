<?php

namespace App\Queries;

use App\Entities\EntityInterface;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): EntityInterface;

    public function getSql(): string;

    public function getParams(QueryInterface $query): array;
}
