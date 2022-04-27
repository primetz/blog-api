<?php

namespace App\Http\Auth;

class Authenticable implements AuthenticableInterface
{
    public function __construct(
        protected string $password
    )
    {
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
