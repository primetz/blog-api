<?php

namespace App\Queries\User;

use App\Queries\QueryInterface;

class FindUserByEmailQuery implements QueryInterface
{
    public function __construct(
        private string $email
    )
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
