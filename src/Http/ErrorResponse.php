<?php

namespace App\Http;

use JetBrains\PhpStorm\ArrayShape;

class ErrorResponse extends Response
{
    protected const SUCCESS = false;

    public function __construct(
        private string $reason = 'Something goes wrong',
    )
    {
    }

    #[ArrayShape(['reason' => "string"])]
    function payload(): array
    {
        return ['reason' => $this->reason];
    }
}
