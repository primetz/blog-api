<?php

namespace App\Entities\Post;

use App\Entities\EntityInterface;

interface PostInterface extends EntityInterface
{
    public function getAuthorId(): int;

    public function getTitle(): string;

    public function getText(): string;
}
