<?php

namespace App\Entities\Like;

class Like implements LikeInterface
{
    private ?int $id;

    public function __construct(
        private int $userId,
        private int $postId,
        ?int $id = null
    )
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function __toString(): string
    {
        return sprintf(
            '[%d] %d %d',
            $this->getId(),
            $this->getUserId(),
            $this->getPostId()
        ) . PHP_EOL;
    }
}
