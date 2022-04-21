<?php

namespace App\Repositories\PostRepository;

use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\Post\PostInterface;
use App\Exceptions\PostNotFoundException;
use App\Repositories\EntityRepository;
use PDO;
use PDOStatement;

class PostRepository extends EntityRepository implements PostRepositoryInterface
{

    /**
     * @throws PostNotFoundException
     */
    public function get(int $id): EntityInterface
    {
        $statement = $this->connection
            ->prepare(
                'SELECT * FROM posts WHERE id = :id'
            );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getPost(
            $statement,
            [
                'id' => $id
            ]
        );
    }

    /**
     * @param Post $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connection
            ->prepare(
                'INSERT INTO posts (author_id, title, text) VALUES (:author_id, :title, :text)'
            );

        $statement->execute([
            ':author_id' => $entity->getAuthorId(),
            ':title' => $entity->getTitle(),
            ':text' => $entity->getText()
        ]);

        $this->logger->info(
            'Post created',
            [
                'postId' => $this->connection->lastInsertId(),
                'class' => self::class,
                'method' => __METHOD__
            ]
        );
    }

    /**
     * @throws PostNotFoundException
     */
    private function getPost(PDOStatement $statement, array $params): PostInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $this->logger->warning(
                'Failed to find post',
                [
                    'params' => $params,
                    'class' => self::class,
                    'method' => __METHOD__,
                    'caller_method' => $backtrace['class'] . '::' . $backtrace['function']
                ]
            );

            throw new PostNotFoundException('Can\'t find post');
        }

        return new Post(
            $result['author_id'],
            $result['title'],
            $result['text'],
            $result['id']
        );
    }
}
