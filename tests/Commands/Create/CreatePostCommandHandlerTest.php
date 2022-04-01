<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Entities\Post\Post;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class CreatePostCommandHandlerTest extends TestCase
{
    use DummyConnectorTrait;

    /**
     * @dataProvider PostDataProvider
     */
    public function testItItSavesPostToDatabase(
        Post $post
    ): void
    {
        $createUserCommandHandler = new CreatePostCommandHandler(
            $this->dummyConnector
        );

        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with($createUserCommandHandler->getSql())
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($createUserCommandHandler->getParams($post));

        $createUserCommandHandler->handle(new CreateEntityCommand($post));
    }

    #[Pure]
    public function postDataProvider(): iterable
    {
        return [
            [
                new Post(
                    1,
                    'Post title',
                    'Post text'
                )
            ]
        ];
    }
}
