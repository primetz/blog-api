<?php

namespace App\Entities\Token;

use App\Entities\EntityInterface;
use DateTimeImmutable;
use DateTimeInterface;

class AuthToken implements AuthTokenInterface
{
    public function __construct(
        private readonly string $token,
        private readonly EntityInterface   $user,
        private DateTimeImmutable $expiresOn,
        private readonly ?int $id = null
    )
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): EntityInterface
    {
        return $this->user;
    }

    public function getExpiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function isExpires(): bool
    {
        return new DateTimeImmutable() > $this->getExpiresOn();
    }

    public function expire(): void
    {
        $this->expiresOn = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return sprintf(
            '[%d] %s %s %s',
            $this->getToken(),
            $this->getToken(),
            $this->getUser()->getId(),
            $this->getExpiresOnString()
        );
    }

    public function __serialize(): array
    {
        return [
            'token' => $this->getToken(),
            'userId' => $this->getUser()->getId(),
            'expiresOn' => $this->getExpiresOnString()
        ];
    }

    private function getExpiresOnString(): string
    {
        return $this->getExpiresOn()->format(DateTimeInterface::ATOM);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
