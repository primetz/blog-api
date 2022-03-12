<?php

namespace App\Entities\Post;

use JetBrains\PhpStorm\Pure;

class Post implements PostInterface
{
    private ?int $id;

    public function __construct(
        private int $authorId,
        private string $title,
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
            $this->getTitle(),
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
