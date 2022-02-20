<?php

namespace Blog\Api\Post;

class Post
{
    public function __construct(
        private int $id,
        private int $authorId,
        private string $title,
        private string $body,
    )
    {
    }

    public function __toString(): string
    {
        return $this->title . ' >>> ' . $this->body;
    }
}
