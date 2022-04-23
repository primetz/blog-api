<?php

namespace App\Services;

use App\Classes\ArgumentInterface;

interface ArgumentParserServiceInterface
{
    public function parseRawInput(
        array $rawInput,
        array $scheme,
    ): ArgumentInterface;
}
