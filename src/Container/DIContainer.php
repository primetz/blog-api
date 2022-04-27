<?php

namespace App\Container;

use App\Exceptions\NotFoundException;
use App\Traits\Instance\Instance;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class DIContainer implements ContainerInterface
{
    use Instance;

    private function __construct()
    {
    }

    private array $resolvers = [];

    public function bind(string $id, string|object $class): void
    {
        $this->resolvers[$id] = $class;
    }

    public function get(string $id): object
    {
        if ($this->has($id)) {
            $typeToCreate = $this->resolvers[$id];

            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }

            return $this->get($typeToCreate);
        }

        if (!class_exists($id)) {
            throw new NotFoundException(
                sprintf('Cannot resolve id: %s', $id)
            );
        }

        $reflectionClass = new ReflectionClass($id);

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $id();
        }

        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $parameterId = $parameter->getType()->getName();

            $parameters[] = $this->get($parameterId);
        }

        return new $id(...$parameters);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->resolvers);
    }
}
