<?php

namespace App\Entities\Comment;

use JetBrains\PhpStorm\Pure;

class Comment implements CommentInterface
{
    private ?int $id;

    public function __construct(
        private int $authorId,
        private int $postId,
        private string $text,
        int $id = null,
    )
    {
        $this->id = $id;
    }

    #[Pure] public function __toString(): string
    {
        return sprintf(
            '[%d] %s %s %s',
            $this->getId(),
            $this->getAuthorId(),
            $this->getPostId(),
            $this->getText()
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
