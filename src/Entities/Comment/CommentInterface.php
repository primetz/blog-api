<?php

namespace App\Entities\Comment;

use App\Entities\EntityInterface;

interface CommentInterface extends EntityInterface
{
    public function getAuthorId(): int;

    public function getPostId(): int;

    public function getText(): string;
}
