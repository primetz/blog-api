<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Comment\Comment;

final class CreateCommentCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    public function handle(CommandInterface $command): void
    {
        /**
         * @var CreateEntityCommand $command
         * @var Comment $comment
         */
        $comment = $command->getEntity();

        $this->statement->execute([
            ':authorId' => $comment->getAuthorId(),
            ':postId' => $comment->getPostId(),
            ':text' => $comment->getText(),
        ]);

    }

    protected function getSql(): string
    {
        return 'INSERT INTO comments (author_id, post_id, text) VALUES (:authorId, :postId, :text)';
    }
}
