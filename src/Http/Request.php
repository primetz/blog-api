<?php

namespace App\Http;

use App\Exceptions\HttpException;
use JsonException;

class Request
{
    public function __construct(
        private array $get,
        private array $server,
        private string $body,
    )
    {
    }

    /**
     * @throws HttpException
     */
    public function jsonBody(): array
    {
        try {
            $data = json_decode(
                json: $this->body,
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            throw new HttpException('Cannot decode json body');
        }

        if (!is_array($data)) {
            throw new HttpException('Not an array/object in json body');
        }

        return $data;
    }

    /**
     * @throws HttpException
     */
    public function jsonBodyField(string $field): mixed
    {
        $data = $this->jsonBody();

        if (!array_key_exists($field, $data)) {
            throw new HttpException(
                sprintf('No such field: %s', $field)
            );
        }

        if (empty($data[$field])) {
            throw new HttpException(
                sprintf('Empty field: %s', $field)
            );
        }

        return $data[$field];
    }

    /**
     * @throws HttpException
     */
    public function method(): string
    {
        if (!array_key_exists('REQUEST_METHOD', $this->server)) {
            throw new HttpException('Cannot get method from the request');
        }

        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @throws HttpException
     */
    public function path(): string
    {
        if (!array_key_exists('REQUEST_URI', $this->server)) {
            throw new HttpException('Cannot get path from the request');
        }

        $components = parse_url($this->server['REQUEST_URI']);

        if (!is_array($components) || !array_key_exists('path', $components)) {
            throw new HttpException('Cannot get path from the request');
        }

        return $components['path'];
    }

    /**
     * @throws HttpException
     */
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            throw new HttpException(
                sprintf('No such query param in the request: %s', $param)
            );
        }

        $value = trim($this->get[$param]);

        if (empty($value)) {
            throw new HttpException(
                sprintf('Empty query param in the request: %s', $param)
            );
        }

        return $value;
    }

    /**
     * @throws HttpException
     */
    public function header(string $header): string
    {
        $headerName = mb_strtoupper('http_' . str_replace('-', '_', $header));

        if (!array_key_exists($headerName, $this->server)) {
            throw new HttpException(
                sprintf('No such header in the request: %s', $header)
            );
        }

        $value = trim($this->server[$headerName]);

        if (empty($value)) {
            throw new HttpException(
                sprintf('Empty header in the request: %s', $header)
            );
        }

        return $value;
    }
}
