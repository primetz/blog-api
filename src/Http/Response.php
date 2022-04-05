<?php

namespace App\Http;

use JsonException;

abstract class Response
{
    protected const SUCCESS = true;

    /**
     * @throws JsonException
     */
    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();

        header('Content-type: application/json');

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    abstract function payload(): array;
}
