<?php

namespace App\Factories\PostFactory;

use App\Decorators\PostDecorator\PostDecorator;
use App\Entities\Post\Post;
use App\Entities\Post\PostInterface;
use JetBrains\PhpStorm\Pure;

final class PostFactory implements PostFactoryInterface
{

    #[Pure] public function create(PostDecorator $postDecorator): PostInterface
    {
        return new Post(
            $postDecorator->authorId,
            $postDecorator->title,
            $postDecorator->text,
            $postDecorator->id,
        );
    }
}
