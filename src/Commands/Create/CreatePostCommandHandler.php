<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class CreatePostCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    public function handle(CommandInterface $command): void
    {
        /**
         * @var CreateEntityCommand $command
         * @var Post $post
         */
        $post = $command->getEntity();

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($post)
        );
    }

    public function getSql(): string
    {
        return 'INSERT INTO posts (author_id, title, text) VALUES (:authorId, :title, :text)';
    }

    #[Pure]
    #[ArrayShape([':authorId' => "mixed", ':title' => "mixed", ':text' => "mixed"])]
    public function getParams(EntityInterface $entity): array
    {
        /**
         * @var Post $entity
         */
        return [
            ':authorId' => $entity->getAuthorId(),
            ':title' => $entity->getTitle(),
            ':text' => $entity->getText(),
        ];
    }
}
