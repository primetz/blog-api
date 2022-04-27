<?php

namespace App\Queries\Token;

interface TokenQueryHandlerInterface
{
    public function handle(): array;

    public function getSql(): string;
}
