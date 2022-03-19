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
        $statement = $this->connector->getConnection()
            ->prepare(
                'SELECT * FROM posts WHERE id = :id'
            );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getPost($statement, $id);
    }

    /**
     * @param Post $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connector->getConnection()
            ->prepare(
                'INSERT INTO posts (author_id, title, text) VALUES (:author_id, :title, :text)'
            );

        $statement->execute([
            ':author_id' => $entity->getAuthorId(),
            ':title' => $entity->getTitle(),
            ':text' => $entity->getText()
        ]);
    }

    /**
     * @throws PostNotFoundException
     */
    private function getPost(PDOStatement $statement, int $postId): PostInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new PostNotFoundException(
                sprintf('Cannot find post with id: %s', $postId)
            );
        }

        return new Post(
            $result['author_id'],
            $result['title'],
            $result['text'],
            $result['id']
        );
    }
}
