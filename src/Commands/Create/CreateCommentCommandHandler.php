<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Entities\Comment\CommentInterface;
use App\Entities\EntityInterface;
use App\Repositories\CommentRepository\CommentRepositoryInterface;
use PDOException;

final class CreateCommentCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        ConnectionInterface $connection,
        private readonly CommentRepositoryInterface $commentRepository
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
        return $this->commentRepository->get($id);
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
