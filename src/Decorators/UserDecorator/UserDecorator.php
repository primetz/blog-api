<?php

namespace App\Decorators\UserDecorator;

use App\Decorators\Decorator;
use App\Decorators\DecoratorInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;

class UserDecorator extends Decorator implements DecoratorInterface
{
    public const ID = 'id';

    public const FIRST_NAME = 'firstName';

    public const LAST_NAME = 'lastName';

    public const EMAIL = 'email';

    public ?int $id;

    public string $firstName;

    public string $lastName;

    public string $email;

    public const REQUIRED_FIELDS = [
        self::FIRST_NAME,
        self::LAST_NAME,
        self::EMAIL,
    ];

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $userFieldData = $this->getFieldData();

        $this->id = $userFieldData->get(self::ID) ?? null;

        $this->firstName = $userFieldData->get(self::FIRST_NAME) ?? null;

        $this->lastName = $userFieldData->get(self::LAST_NAME) ?? null;

        $this->email = $userFieldData->get(self::EMAIL) ?? null;
    }

    public function getRequiredFields(): array
    {
        return self::REQUIRED_FIELDS;
    }
}
