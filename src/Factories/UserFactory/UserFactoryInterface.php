<?php

namespace App\Factories\UserFactory;

use App\Decorators\UserDecorator\UserDecorator;
use App\Entities\User\UserInterface;
use App\Factories\FactoryInterface;

interface UserFactoryInterface extends FactoryInterface
{
    public function create(UserDecorator $userDecorator): UserInterface;
}
