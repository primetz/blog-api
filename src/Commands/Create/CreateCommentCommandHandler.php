<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class CreateCommentCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    public function handle(CommandInterface $command): void
    {
        /**
         * @var CreateEntityCommand $command
         * @var Comment $comment
         */
        $comment = $command->getEntity();

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($comment)
        );

    }

    #[Pure]
    #[ArrayShape([':authorId' => "int", ':postId' => "int", ':text' => "string"])]
    public function getParams(EntityInterface $entity): array
    {
        /**
         * @var Comment $entity
         */
        return [
            ':authorId' => $entity->getAuthorId(),
            ':postId' => $entity->getPostId(),
            ':text' => $entity->getText(),
        ];
    }

    public function getSql(): string
    {
        return 'INSERT INTO comments (author_id, post_id, text) VALUES (:authorId, :postId, :text)';
    }
}
