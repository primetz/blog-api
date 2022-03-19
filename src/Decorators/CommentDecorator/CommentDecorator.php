<?php

namespace App\Decorators\CommentDecorator;

use App\Decorators\Decorator;
use App\Decorators\DecoratorInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserServiceInterface;

class CommentDecorator extends Decorator implements DecoratorInterface
{
    public const ID = 'id';

    public const AUTHOR_ID = 'authorId';

    public const POST_ID = 'postId';

    public const TEXT = 'text';

    public ?int $id;

    public int $authorId;

    public int $postId;

    public string $text;

    public const REQUIRED_FIELDS = [
        self::AUTHOR_ID,
        self::POST_ID,
        self::TEXT,
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

        $this->authorId = $userFieldData->get(self::AUTHOR_ID) ?? null;

        $this->postId = $userFieldData->get(self::POST_ID) ?? null;

        $this->text = $userFieldData->get(self::TEXT) ?? null;
    }

    public function getRequiredFields(): array
    {
        return self::REQUIRED_FIELDS;
    }
}
