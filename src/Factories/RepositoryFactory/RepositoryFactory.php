<?php

namespace App\Factories\RepositoryFactory;

use App\Drivers\ConnectionInterface;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Entities\Like\Like;
use App\Entities\Post\Post;
use App\Entities\User\User;
use App\Repositories\CommentRepository\CommentRepository;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\LikeRepository\LikeRepository;
use App\Repositories\PostRepository\PostRepository;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    #[Pure(true)] public function create(EntityInterface $entity): EntityRepositoryInterface
    {
        return match ($entity::class) {
            User::class => new UserRepository(),
            Post::class => new PostRepository(),
            Comment::class => new CommentRepository(),
            Like::class => new LikeRepository()
        };
    }
}
