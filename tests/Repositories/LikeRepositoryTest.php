<?php

namespace Tests\Repositories;

use App\Entities\Like\Like;
use App\Exceptions\LikeNotFoundException;
use App\Repositories\LikeRepository\LikeRepository;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;
use Tests\DummyLogger;

class LikeRepositoryTest extends TestCase
{
    use DummyConnectorTrait;

    public function testItThrowsAnLikeNotFoundExceptionWhenLikeNotFoundById(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(LikeNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find like');

        $likeRepository = new LikeRepository(
            $this->connection,
            new DummyLogger()
        );

        $likeRepository->get(1);
    }

    public function testItThrowsAnLikeNotFoundExceptionWhenLikeNotFoundByUserIdAndPostId(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(LikeNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find like');

        $likeRepository = new LikeRepository(
            $this->connection,
            new DummyLogger()
        );

        $likeRepository->getByUserIdAndPostId(1, 1);
    }

    /**
     * @dataProvider likeDataProvider
     * @throws LikeNotFoundException
     */
    public function testItReturnsLikeById(
        array $inputValue,
        Like $expectedLike
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM likes WHERE id = :id'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $likeRepository = new LikeRepository(
            $this->connection
        );

        $like = $likeRepository->get($expectedLike->getId());

        $this->assertEquals($expectedLike, $like);
    }

    /**
     * @dataProvider likeDataProvider
     * @throws LikeNotFoundException
     */
    public function testItReturnsLikeByUserIdAndPostId(
        array $inputValue,
        Like $expectedLike
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $likeRepository = new LikeRepository(
            $this->connection
        );

        $like = $likeRepository->getByUserIdAndPostId(
            $expectedLike->getUserId(),
            $expectedLike->getPostId()
        );

        $this->assertEquals($expectedLike, $like);
    }

    /**
     * @dataProvider likeDataProvider
     */
    public function testItSavesLikeToDatabase(
        array $inputValue,
        Like $like,
        array $executeValue
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($executeValue);

        $likeRepository = new LikeRepository(
            $this->connection,
            new DummyLogger()
        );

        $likeRepository->save($like);
    }

    public function likeDataProvider(): iterable
    {
        $like = new Like(
            1,
            1,
            1,
        );

        return [
            [
                [
                    'id' => $like->getId(),
                    'user_id' => $like->getUserId(),
                    'post_id' => $like->getPostId(),
                ],
                $like,
                [
                    ':user_id' => $like->getUserId(),
                    ':post_id' => $like->getPostId(),
                ]
            ]
        ];
    }
}
