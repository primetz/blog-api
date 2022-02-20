<?php

use Blog\Api\Comment\Comment;
use Blog\Api\Post\Post;
use Blog\Api\User\User;
use Faker\Factory;

require_once 'vendor/autoload.php';

$faker = Factory::create();

print match ($argv[1] ?? null) {
    'user' => new User(
            $faker->randomDigitNotNull(),
            $faker->firstName(),
            $faker->lastName(),
        ) . PHP_EOL,
    'post' => new Post(
            $faker->randomDigitNotNull(),
            $faker->randomDigitNotNull(),
            $faker->sentence(),
            $faker->text(),
        ) . PHP_EOL,
    'comment' => new Comment(
            $faker->randomDigitNotNull(),
            $faker->randomDigitNotNull(),
            $faker->randomDigitNotNull(),
            $faker->sentence(),
        ) . PHP_EOL,
    default => 'Missing argument: php cli.php {user|post|comment}' . PHP_EOL,
};
