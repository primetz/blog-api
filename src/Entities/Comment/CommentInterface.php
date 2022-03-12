<?php

namespace App\Entities\Comment;

use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\User\User;

interface CommentInterface extends EntityInterface
{
    public function getAuthorId(): int;

    public function getPostId(): int;

    public function getText(): string;
}
