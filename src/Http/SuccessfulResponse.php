<?php

namespace App\Http;

use JetBrains\PhpStorm\ArrayShape;

class SuccessfulResponse extends Response
{
    public function __construct(
        private array $data = []
    )
    {
    }

    #[ArrayShape(['data' => "array"])]
    function payload(): array
    {
        return ['data' => $this->data];
    }
}
