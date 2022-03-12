<?php

namespace App\Entities\User;

use JetBrains\PhpStorm\Pure;

class User implements UserInterface
{
    private ?int $id;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        int $id = null
    )
    {
        $this->id = $id;
    }

    #[Pure] public function __toString(): string
    {
        return sprintf(
            '[%d] %s %s %s',
            $this->getId(),
            $this->getFirstName(),
            $this->getLastName(),
            $this->getEmail()
        ) . PHP_EOL;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
