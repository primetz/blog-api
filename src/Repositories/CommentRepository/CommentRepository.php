<?php

namespace App\Repositories\CommentRepository;

use App\Entities\Comment\Comment;
use App\Entities\Comment\CommentInterface;
use App\Entities\EntityInterface;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\EntityRepository;
use PDO;
use PDOStatement;

class CommentRepository extends EntityRepository implements CommentRepositoryInterface
{

    /**
     * @throws CommentNotFoundException
     */
    public function get(int $id): EntityInterface
    {
        $statement = $this->connection
            ->prepare(
                'SELECT * FROM comments WHERE id = :id'
            );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getComment(
            $statement,
            [
                'id' => $id
            ]
        );
    }

    /**
     * @param Comment $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connection
            ->prepare(
                'INSERT INTO comments (author_id, post_id, text) VALUES (:author_id, :post_id, :text)'
            );

        $statement->execute([
            ':author_id' => $entity->getAuthorId(),
            ':post_id' => $entity->getPostId(),
            ':text' => $entity->getText()
        ]);

        $this->logger->info(
            'Comment created',
            [
                'commentId' => $this->connection->lastInsertId(),
                'class' => self::class,
                'method' => __METHOD__
            ]
        );
    }

    /**
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement, array $params): CommentInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $this->logger->warning(
                'Failed to find comment',
                [
                    'params' => $params,
                    'class' => self::class,
                    'method' => __METHOD__,
                    'caller_method' => $backtrace['class'] . '::' . $backtrace['function']
                ]
            );

            throw new CommentNotFoundException('Can\'t find comment');
        }

        return new Comment(
            $result['author_id'],
            $result['post_id'],
            $result['text'],
            $result['id'],
        );
    }
}
