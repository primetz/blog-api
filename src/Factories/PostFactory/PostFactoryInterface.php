<?php

namespace App\Factories\PostFactory;

use App\Decorators\PostDecorator\PostDecorator;
use App\Entities\Post\PostInterface;
use App\Factories\FactoryInterface;

interface PostFactoryInterface extends FactoryInterface
{
    public function create(PostDecorator $postDecorator): PostInterface;
}
