<?php

namespace App\Commands;

use App\Entities\EntityInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;

    public function getParams(EntityInterface $entity): array;

    public function getSql(): string;
}
