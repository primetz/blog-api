<?php

namespace App\Factories\LikeFactory;

use App\Decorators\LikeDecorator\LikeDecorator;
use App\Entities\Like\LikeInterface;
use App\Factories\FactoryInterface;

interface LikeFactoryInterface extends FactoryInterface
{
    public function create(LikeDecorator $likeDecorator): LikeInterface;
}
