<?php

namespace App\Enums;

enum Argument: string
{
    case USER = 'user';

    case POST = 'post';

    case COMMENT = 'comment';

    public const ARGUMENTS = [
        Argument::USER,
        Argument::POST,
        Argument::COMMENT,
    ];

    public static function getArgumentValues(): array
    {
        return [
            Argument::USER->value,
            Argument::POST->value,
            Argument::COMMENT->value,
        ];
    }
}
