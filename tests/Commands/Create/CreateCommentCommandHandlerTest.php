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
     * @dataProvider commentDataProvider
     */
    public function testItItSavesCommentToDatabase(
        Comment $comment
    ): void
    {
        $createCommentCommandHandler = new CreateCommentCommandHandler(
            $this->dummyConnector
        );

        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with($createCommentCommandHandler->getSql())
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($createCommentCommandHandler->getParams($comment));

        $createCommentCommandHandler->handle(new CreateEntityCommand($comment));
    }

    #[Pure]
    public function commentDataProvider(): iterable
    {
        return [
            [
                new Comment(
                    1,
                    1,
                    'Comment text'
                )
            ]
        ];
    }
}
