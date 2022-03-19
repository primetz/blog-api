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
        $statement = $this->connector->getConnection()
            ->prepare(
                'SELECT * FROM comments WHERE id = :id'
            );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getComment($statement, $id);
    }

    /**
     * @param Comment $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connector->getConnection()
            ->prepare(
                'INSERT INTO comments (author_id, post_id, text) VALUES (:author_id, :post_id, :text)'
            );

        $statement->execute([
            ':author_id' => $entity->getAuthorId(),
            ':post_id' => $entity->getPostId(),
            ':text' => $entity->getText()
        ]);
    }

    /**
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement, int $commentId): CommentInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new CommentNotFoundException(
                sprintf('Cannot find comment with id: %s', $commentId)
            );
        }

        return new Comment(
            $result['author_id'],
            $result['post_id'],
            $result['text'],
            $result['id'],
        );
    }
}
