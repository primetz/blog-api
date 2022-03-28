<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Post\Post;

final class CreatePostCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    public function handle(CommandInterface $command): void
    {
        /**
         * @var CreateEntityCommand $command
         * @var Post $post
         */
        $post = $command->getEntity();

        $this->statement->execute([
            ':authorId' => $post->getAuthorId(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    protected function getSql(): string
    {
        return 'INSERT INTO posts (author_id, title, text) VALUES (:authorId, :title, :text)';
    }
}
