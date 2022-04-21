<?php

namespace Tests\Repositories;

use App\Entities\Post\Post;
use App\Exceptions\PostNotFoundException;
use App\Repositories\PostRepository\PostRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;
use Tests\DummyLogger;

class PostRepositoryTest extends TestCase
{
    use DummyConnectorTrait;

    public function testItThrowsAPostNotFoundExceptionWhenPostNotFoundById(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(PostNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find post');

        $postRepository = new PostRepository(
            $this->connection,
            new DummyLogger()
        );

        $postRepository->get(1);
    }

    /**
     * @dataProvider postProvider
     * @throws PostNotFoundException
     */
    public function testItReturnsPostById(
        array $inputValue,
        Post $expectedPost
    ):void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM posts WHERE id = :id'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $postRepository = new PostRepository(
            $this->connection
        );

        $post = $postRepository->get($expectedPost->getId());

        $this->assertEquals($expectedPost, $post);
    }

    /**
     * @dataProvider postProvider
     */
    public function testItSavesPostToDatabase(
        array $inputValue,
        Post $post,
        array $executeValue
    ): void
    {
        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'INSERT INTO posts (author_id, title, text) VALUES (:author_id, :title, :text)'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($executeValue);

        $postRepository = new PostRepository(
            $this->connection,
            new DummyLogger()
        );

        $postRepository->save($post);
    }

    #[Pure] public function postProvider(): iterable
    {
        $post = new Post(
            1,
            'Post title',
            'Post text',
            1
        );

        return [
            [
                [
                    'id' => $post->getId(),
                    'author_id' => $post->getAuthorId(),
                    'title' => $post->getTitle(),
                    'text' => $post->getText(),
                ],
                $post,
                [
                    ':author_id' => $post->getAuthorId(),
                    ':title' => $post->getTitle(),
                    ':text' => $post->getText(),
                ]
            ]
        ];
    }
}
