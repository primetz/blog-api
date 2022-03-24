<?php

namespace App\Factories\RepositoryFactory;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\User\User;
use App\Repositories\CommentRepository\CommentRepository;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\PostRepository\PostRepository;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    #[Pure(true)] public function create(EntityInterface $entity): EntityRepositoryInterface
    {
        return match ($entity::class) {
            User::class => new UserRepository($this->connector),
            Post::class => new PostRepository($this->connector),
            Comment::class => new CommentRepository($this->connector)
        };
    }
}
