<?php

namespace App\Queries\Post;

use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\Post\PostInterface;
use App\Exceptions\PostNotFoundException;
use App\Queries\FindByIdQuery;
use App\Queries\QueryHandler;
use App\Queries\QueryHandlerInterface;
use App\Queries\QueryInterface;
use PDO;
use PDOStatement;

class FindPostByIdQueryHandler extends QueryHandler implements QueryHandlerInterface
{

    /**
     * @throws PostNotFoundException
     */
    public function handle(QueryInterface $query): EntityInterface
    {
        $statement = $this->connection->prepare(
            $this->getSql()
        );

        $statement->execute(
            $this->getParams($query)
        );

        return $this->getPost($statement);
    }

    public function getSql(): string
    {
        return 'SELECT * FROM posts WHERE id = :id';
    }

    /**
     * @param FindByIdQuery $query
     */
    public function getParams(QueryInterface $query): array
    {
        return [
            ':id' => $query->getId()
        ];
    }

    /**
     * @throws PostNotFoundException
     */
    private function getPost(PDOStatement $statement): PostInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
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
