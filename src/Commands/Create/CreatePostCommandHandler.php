<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Entities\EntityInterface;
use App\Entities\Post\PostInterface;
use App\Repositories\PostRepository\PostRepositoryInterface;
use PDOException;

final class CreatePostCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        ConnectionInterface $connection,
        private PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($connection);
    }

    public function handle(CommandInterface $command): EntityInterface
    {
        try {
            $this->connection->beginTransaction();

            $this->connection->executeQuery(
                $this->getSql(),
                $this->getParams($command)
            );

            $id = $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();

            print 'Error!: ' . $e->getMessage() . PHP_EOL;
        }

        /**
         * @var int $id
         */
        return $this->postRepository->get($id);
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
