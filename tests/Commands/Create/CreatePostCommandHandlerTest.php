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
     * @dataProvider commandDataProvider
     */
    public function testItItSavesPostToDatabase(
        CreateEntityCommand $command
    ): void
    {
        $createUserCommandHandler = new CreatePostCommandHandler(
            $this->connection
        );

        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with($createUserCommandHandler->getSql());

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($createUserCommandHandler->getParams($command));

        $createUserCommandHandler->handle($command);
    }

    #[Pure]
    public function commandDataProvider(): iterable
    {
        return [
            [
                new CreateEntityCommand(
                    new Post(
                        1,
                        'Post title',
                        'Post text'
                    )
                )
            ]
        ];
    }
}
