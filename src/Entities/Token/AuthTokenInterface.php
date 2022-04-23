<?php

namespace App\Entities\Token;

use App\Entities\EntityInterface;
use DateTimeImmutable;

interface AuthTokenInterface extends EntityInterface
{
    public function getToken(): string;

    public function getUser(): EntityInterface;

    public function getExpiresOn(): DateTimeImmutable;

    public function isExpires(): bool;

    public function expire(): void;

    public function __toString(): string;

    public function __serialize(): array;
}
