<?php

namespace App\Factories\EntityFactory;

use App\Decorators\CommentDecorator\CommentDecorator;
use App\Decorators\LikeDecorator\LikeDecorator;
use App\Decorators\PostDecorator\PostDecorator;
use App\Decorators\UserDecorator\UserDecorator;
use App\Entities\EntityInterface;
use App\Enums\Argument;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Exceptions\MatchException;
use App\Factories\CommentFactory\CommentFactory;
use App\Factories\CommentFactory\CommentFactoryInterface;
use App\Factories\LikeFactory\LikeFactory;
use App\Factories\LikeFactory\LikeFactoryInterface;
use App\Factories\PostFactory\PostFactory;
use App\Factories\PostFactory\PostFactoryInterface;
use App\Factories\UserFactory\UserFactory;
use App\Factories\UserFactory\UserFactoryInterface;
use JetBrains\PhpStorm\Pure;

class EntityFactory implements EntityFactoryInterface
{
    private ?UserFactoryInterface $userFactory;

    private ?PostFactoryInterface $postFactory;

    private ?CommentFactoryInterface $commentFactory;

    private LikeFactoryInterface $likeFactory;

    #[Pure] public function __construct(
        UserFactoryInterface $userFactory = null,
        PostFactoryInterface $postFactory = null,
        CommentFactoryInterface $commentFactory = null,
        ?LikeFactoryInterface $likeFactory = null
    )
    {
        $this->userFactory = $userFactory ?? new UserFactory();

        $this->postFactory = $postFactory ?? new PostFactory();

        $this->commentFactory = $commentFactory ?? new CommentFactory();

        $this->likeFactory = $likeFactory ?? new LikeFactory();
    }

    /**
     * @throws CommandException
     * @throws MatchException
     * @throws ArgumentException
     */
    public function create(string $entityType, array $arguments): EntityInterface
    {
        return match ($entityType) {
            Argument::USER->value => $this->userFactory->create(new UserDecorator($arguments)),
            Argument::POST->value => $this->postFactory->create(new PostDecorator($arguments)),
            Argument::COMMENT->value => $this->commentFactory->create(new CommentDecorator($arguments)),
            Argument::LIKE->value => $this->likeFactory->create(new LikeDecorator($arguments)),
            default => throw new MatchException(
                sprintf(
                    'The argument must contain one of the listed values: %s',
                    implode(', ', array_map(fn(Argument $argument) => $argument->value, Argument::ARGUMENTS))
                )
            )
        };
    }
}
