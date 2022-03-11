<?php

namespace App\Factories\EntityManagerFactory;

use App\Entities\EntityInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Exceptions\MatchException;
use App\Factories\EntityFactory\EntityFactory;
use App\Factories\EntityFactory\EntityFactoryInterface;
use App\Factories\Factory;
use App\Factories\RepositoryFactory\RepositoryFactory;
use App\Factories\RepositoryFactory\RepositoryFactoryInterface;
use App\Repositories\EntityRepositoryInterface;
use JetBrains\PhpStorm\Pure;

class EntityManagerFactory extends Factory implements EntityManagerFactoryInterface
{
    private ?EntityFactoryInterface $entityFactory;

    private ?RepositoryFactoryInterface $repositoryFactory;

    #[Pure] protected function __construct(
        EntityFactoryInterface $entityFactory = null,
        RepositoryFactoryInterface $repositoryFactory = null,
    )
    {
        $this->entityFactory = $entityFactory ?? new EntityFactory();

        $this->repositoryFactory = $repositoryFactory ?? new RepositoryFactory();
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     * @throws MatchException
     */
    public function createEntity(string $entityType, array $arguments): EntityInterface
    {
        return $this->entityFactory->create($entityType, $arguments);
    }

    public function getRepository(EntityInterface $entity): EntityRepositoryInterface
    {
        return $this->repositoryFactory->create($entity);
    }

    /**
     * @throws CommandException
     * @throws MatchException
     * @throws ArgumentException
     */
    public function createEntityByInputArguments(array $arguments): EntityInterface
    {
        return $this->createEntity($arguments[1], array_slice($arguments, 2));
    }

    /**
     * @throws MatchException
     * @throws CommandException
     * @throws ArgumentException
     */
    public function getRepositoryByInputArguments(array $arguments): EntityRepositoryInterface
    {
        return $this->getRepository($this->createEntityByInputArguments($arguments));
    }
}
