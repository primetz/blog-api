<?php

namespace Blog\Api\Comment;

class Comment
{
    public function __construct(
        private int $id,
        private int $authorId,
        private int $postId,
        private string $body,
    )
    {
    }

    public function __toString(): string
    {
        return $this->body;
    }
}
