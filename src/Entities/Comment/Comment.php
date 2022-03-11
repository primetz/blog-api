<?php

namespace App\Entities\Comment;

use App\Entities\Post\Post;
use App\Entities\User\User;
use JetBrains\PhpStorm\Pure;

class Comment implements CommentInterface
{
    public function __construct(
        private int $id,
        private User $author,
        private Post $post,
        private string $text,
    )
    {
    }

    #[Pure] public function __toString(): string
    {
        return sprintf(
            '[%d] %s %s %s',
            $this->getId(),
            $this->getAuthor(),
            $this->getPost(),
            $this->getText()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
