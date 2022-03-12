<?php

namespace App\Factories\CommentFactory;

use App\Decorators\CommentDecorator\CommentDecorator;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use JetBrains\PhpStorm\Pure;

final class CommentFactory implements CommentFactoryInterface
{

    #[Pure] public function create(CommentDecorator $commentDecorator): EntityInterface
    {
        return new Comment(
            $commentDecorator->authorId,
            $commentDecorator->postId,
            $commentDecorator->text,
            $commentDecorator->id,
        );
    }
}
