<?php

namespace App\Entities\Post;

use App\Entities\EntityInterface;
use App\Entities\User\User;

interface PostInterface extends EntityInterface
{
    public function getAuthor(): User;

    public function getTitle(): string;

    public function getText(): string;
}
