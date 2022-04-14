<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Post\PostInterface;

final class CreatePostCommandHandler extends CommandHandler implements CommandHandlerInterface
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
        return 'INSERT INTO posts (author_id, title, text) VALUES (:authorId, :title, :text)';
    }

    /**
     * @param CreateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var PostInterface $post
         */
        $post = $command->getEntity();

        return [
            ':authorId' => $post->getAuthorId(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ];
    }
}
