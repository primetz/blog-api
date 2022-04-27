<?php

namespace App\Commands;

use App\Entities\EntityInterface;

interface CommandHandlerInterface
{
//    public function handle(CommandInterface $command): EntityInterface;

    public function getParams(CommandInterface $command): array;

    public function getSql(): string;
}
