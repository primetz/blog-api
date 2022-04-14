<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Comment\CommentInterface;

final class CreateCommentCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    public function handle(CommandInterface $command): void
    {
        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    public function getSql(): string
    {
        return 'INSERT INTO comments (author_id, post_id, text) VALUES (:authorId, :postId, :text)';
    }

    /**
     * @param CreateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var CommentInterface $comment
         */
        $comment = $command->getEntity();

        return [
            ':authorId' => $comment->getAuthorId(),
            ':postId' => $comment->getPostId(),
            ':text' => $comment->getText(),
        ];
    }
}
