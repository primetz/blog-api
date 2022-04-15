<?php

namespace App\Decorators\LikeDecorator;

use App\Decorators\Decorator;
use App\Decorators\DecoratorInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;

class LikeDecorator extends Decorator implements DecoratorInterface
{
    public const ID = 'id';

    public const USER_ID = 'userId';

    public const POST_ID = 'postId';

    public ?int $id;

    public int $userId;

    public int $postId;

    public const REQUIRED_FIELDS = [
        self::USER_ID,
        self::POST_ID,
    ];

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);

        $userFieldData = $this->getFieldData();

        $this->id = $userFieldData->get(self::ID) ?? null;

        $this->userId = $userFieldData->get(self::USER_ID) ?? null;

        $this->postId = $userFieldData->get(self::POST_ID) ?? null;
    }

    public function getRequiredFields(): array
    {
        return self::REQUIRED_FIELDS;
    }
}
