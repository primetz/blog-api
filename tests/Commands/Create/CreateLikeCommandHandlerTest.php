<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateLikeCommandHandler;
use App\Entities\Like\Like;
use App\Exceptions\LikeExistsException;
use App\Exceptions\LikeNotFoundException;
use App\Repositories\LikeRepository\LikeRepository;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class CreateLikeCommandHandlerTest extends TestCase
{
    use DummyConnectorTrait;

    /**
     * @dataProvider commandDataProvider
     */
    public function testItThrowsAnLikeExistsExceptionWhenLikeAlreadyExists(
        CreateEntityCommand $command,
        Like $like
    ): void
    {
        $likeRepositoryStub = $this->createStub(LikeRepository::class);

        $likeRepositoryStub->method('getByUserIdAndPostId')->willReturn($like);

        $this->expectException(LikeExistsException::class);

        $this->expectExceptionMessage('You can\'t like more than one post');

        $createLikeCommandHandler = new CreateLikeCommandHandler($likeRepositoryStub);

        $createLikeCommandHandler->handle($command);
    }

    /**
     * @dataProvider commandDataProvider
     * @throws LikeExistsException
     */
    public function testItItSavesLikeToDatabase(
        CreateEntityCommand $command
    ): void
    {
        $likeRepositoryStub = $this->createStub(LikeRepository::class);

        $likeRepositoryStub->method('getByUserIdAndPostId')->willThrowException(new LikeNotFoundException());

        $createLikeCommandHandler = new CreateLikeCommandHandler(
            $likeRepositoryStub,
            $this->connection
        );

        $this->PDOMock
            ->expects($this->once())
            ->method('prepare')
            ->with($createLikeCommandHandler->getSql());

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($createLikeCommandHandler->getParams($command));

        $createLikeCommandHandler->handle($command);
    }

    public function commandDataProvider(): iterable
    {
        $like = new Like(
            1,
            1,
        );

        return [
            [
                new CreateEntityCommand($like),
                $like
            ]
        ];
    }
}
