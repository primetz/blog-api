<?php

namespace App\Commands\Update;

use App\Commands\CommandInterface;
use App\Entities\EntityInterface;

class UpdateEntityCommand implements CommandInterface
{
    public function __construct(
        private EntityInterface $entity
    )
    {
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
