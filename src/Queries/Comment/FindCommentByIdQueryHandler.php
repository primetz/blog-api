<?php

namespace App\Queries\Comment;

use App\Entities\Comment\Comment;
use App\Entities\Comment\CommentInterface;
use App\Entities\EntityInterface;
use App\Exceptions\CommentNotFoundException;
use App\Queries\FindByIdQuery;
use App\Queries\QueryHandler;
use App\Queries\QueryHandlerInterface;
use App\Queries\QueryInterface;
use PDO;
use PDOStatement;

class FindCommentByIdQueryHandler extends QueryHandler implements QueryHandlerInterface
{

    /**
     * @throws CommentNotFoundException
     */
    public function handle(QueryInterface $query): EntityInterface
    {
        $statement = $this->connection->prepare(
            $this->getSql()
        );

        $statement->execute(
            $this->getParams($query)
        );

        return $this->getComment($statement);
    }

    public function getSql(): string
    {
        return 'SELECT * FROM comments WHERE id = :id';
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
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement): CommentInterface
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
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
