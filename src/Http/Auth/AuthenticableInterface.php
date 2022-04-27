<?php

namespace App\Http\Auth;

interface AuthenticableInterface
{
    public function getPassword(): string;

    public function hashPassword(string $password): string;

    public function verifyPassword(string $password): bool;
}
