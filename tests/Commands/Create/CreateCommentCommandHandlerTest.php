<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Entities\Comment\Comment;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class CreateCommentCommandHandlerTest extends TestCase
{
    use DummyConnectorTrait;

    /**
     * @dataProvider commandDataProvider
     */
    public function testItItSavesCommentToDatabase(
        CreateEntityCommand $command
    ): void
    {
        $createCommentCommandHandler = new CreateCommentCommandHandler(
            $this->connection
        );

        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with($createCommentCommandHandler->getSql());

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($createCommentCommandHandler->getParams($command));

        $createCommentCommandHandler->handle($command);
    }

    #[Pure]
    public function commandDataProvider(): iterable
    {
        return [
            [
                new CreateEntityCommand(
                    new Comment(
                        1,
                        1,
                        'Comment text'
                    )
                )
            ]
        ];
    }
}
