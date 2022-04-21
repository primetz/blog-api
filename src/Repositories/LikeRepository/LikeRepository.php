<?php

namespace App\Repositories\LikeRepository;

use App\Entities\EntityInterface;
use App\Entities\Like\Like;
use App\Entities\Like\LikeInterface;
use App\Exceptions\LikeNotFoundException;
use App\Repositories\EntityRepository;
use PDO;
use PDOStatement;

class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{

    /**
     * @throws LikeNotFoundException
     */
    public function get(int $id): EntityInterface
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string) $id
        ]);

        return $this->getLike(
            $statement,
            [
                'id' => $id
            ]
        );
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getByUserIdAndPostId(int $userId, int $postId): EntityInterface
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id'
        );

        $statement->execute([
            ':user_id' => (string) $userId,
            ':post_id' => (string) $postId,
        ]);

        return $this->getLike(
            $statement,
            [
                'userId' => $userId,
                'postId' => $postId
            ]
        );
    }

    /**
     * @param Like $entity
     */
    public function save(EntityInterface $entity): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)'
        );

        $statement->execute([
            ':user_id' => $entity->getUserId(),
            ':post_id' => $entity->getPostId()
        ]);

        $this->logger->info(
            'Like created',
            [
                'likeId' => $this->connection->lastInsertId(),
                'class' => self::class,
                'method' => __METHOD__
            ]
        );
    }

    /**
     * @throws LikeNotFoundException
     */
    private function getLike(PDOStatement $statement, array $params): LikeInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $this->logger->warning(
                'Failed to find like',
                [
                    'params' => $params,
                    'class' => self::class,
                    'method' => __METHOD__,
                    'caller_method' => $backtrace['class'] . '::' . $backtrace['function']
                ]
            );

            throw new LikeNotFoundException('Can\'t find like');
        }

        return new Like(
            $result['user_id'],
            $result['post_id'],
            $result['id'],
        );
    }
}
