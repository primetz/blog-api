<?php

namespace App\Entities\Post;

use App\Entities\User\User;
use JetBrains\PhpStorm\Pure;

class Post implements PostInterface
{
    public function __construct(
        private int $id,
        private User $author,
        private string $title,
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
            $this->getTitle(),
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
