<?php

namespace App\Factories\LikeFactory;

use App\Decorators\LikeDecorator\LikeDecorator;
use App\Entities\Like\Like;
use App\Entities\Like\LikeInterface;

class LikeFactory implements LikeFactoryInterface
{
    public function create(LikeDecorator $likeDecorator): LikeInterface
    {
        return new Like(
            $likeDecorator->userId,
            $likeDecorator->postId,
            $likeDecorator->id,
        );
    }
}
