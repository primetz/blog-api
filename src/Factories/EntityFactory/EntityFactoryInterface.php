<?php

namespace App\Factories\EntityFactory;

use App\Entities\EntityInterface;

interface EntityFactoryInterface
{
    public function create(string $entityType, array $arguments): EntityInterface;
}
