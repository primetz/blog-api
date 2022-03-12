<?php

namespace App\Factories\CommentFactory;


use App\Decorators\CommentDecorator\CommentDecorator;
use App\Entities\EntityInterface;
use App\Factories\FactoryInterface;

interface CommentFactoryInterface extends FactoryInterface
{
    public function create(CommentDecorator $commentDecorator): EntityInterface;
}
