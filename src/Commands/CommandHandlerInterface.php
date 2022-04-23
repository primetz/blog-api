<?php

namespace App\Commands;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;

    public function getParams(CommandInterface $command): array;

    public function getSql(): string;
}
