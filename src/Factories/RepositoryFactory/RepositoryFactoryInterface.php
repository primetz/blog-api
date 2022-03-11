<?php

namespace App\Factories\RepositoryFactory;

use App\Entities\EntityInterface;
use App\Repositories\EntityRepositoryInterface;

interface RepositoryFactoryInterface
{
    public function create(EntityInterface $entity): EntityRepositoryInterface;
}
