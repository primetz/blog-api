<?php

namespace App\Entities\User;

use App\Http\Auth\Authenticable;
use JetBrains\PhpStorm\Pure;

class User extends Authenticable implements UserInterface
{
    private ?int $id;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        string $password,
        int $id = null
    )
    {
        $this->id = $id;

        parent::__construct($password);
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

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
