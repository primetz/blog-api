<?php

namespace Tests\Repositories;

use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepository\CommentRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;
use Tests\DummyLogger;

class CommentRepositoryTest extends TestCase
{
    use DummyConnectorTrait;

    public function testItThrowsACommentNotFoundExceptionWhenCommentNotFoundById(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(CommentNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find comment');

        $commentRepository = new CommentRepository(
            $this->connection,
            new DummyLogger()
        );

        $commentRepository->get(1);
    }

    /**
     * @dataProvider commentProvider
     * @throws CommentNotFoundException
     */
    public function testItReturnsCommentById(
        array $inputValue,
        Comment $expectedComment
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM comments WHERE id = :id'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $commentRepository = new CommentRepository(
            $this->connection
        );

        $comment = $commentRepository->get($expectedComment->getId());

        $this->assertEquals($expectedComment, $comment);
    }

    /**
     * @dataProvider commentProvider
     */
    public function testItSavesCommentToDatabase(
        array $inputValue,
        Comment $comment,
        array $executeValue
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'INSERT INTO comments (author_id, post_id, text) VALUES (:author_id, :post_id, :text)'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($executeValue);

        $commentRepository = new CommentRepository(
            $this->connection,
            new DummyLogger()
        );

        $commentRepository->save($comment);
    }

    #[Pure] public function commentProvider(): iterable
    {
        $comment = new Comment(
            1,
            1,
            'Comment text',
            1
        );

        return [
            [
                [
                    'id' => $comment->getId(),
                    'author_id' => $comment->getAuthorId(),
                    'post_id' => $comment->getPostId(),
                    'text' => $comment->getText()
                ],
                $comment,
                [
                    ':author_id' => $comment->getAuthorId(),
                    ':post_id' => $comment->getPostId(),
                    ':text' => $comment->getText()
                ]
            ]
        ];
    }
}
