<?php

namespace App\Commands\Create;

use App\Commands\CommandInterface;
use App\Entities\EntityInterface;

class CreateEntityCommand implements CommandInterface
{
    public function __construct(private EntityInterface $entity)
    {
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
