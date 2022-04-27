<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistsException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class CreateUserCommandHandlerTest extends TestCase
{
    use DummyConnectorTrait;

    /**
     * @dataProvider commandDataProvider
     */
    public function testItThrowsAnUserEmailExistExceptionWhenUserAlreadyExists(
        CreateEntityCommand $command,
        User $user
    ): void
    {
        $userRepositoryStub = $this->createStub(UserRepository::class);

        $userRepositoryStub->method('getByEmail')->willReturn($user);

        $this->expectException(UserEmailExistsException::class);

        $this->expectExceptionMessage(
            sprintf('User with email %s already exists', $user->getEmail())
        );

        $createUserCommandHandler = new CreateUserCommandHandler($userRepositoryStub);

        $createUserCommandHandler->handle($command);
    }

    /**
     * @dataProvider commandDataProvider
     * @throws UserEmailExistsException
     */
    public function testItItSavesUserToDatabase(
        CreateEntityCommand $command
    ): void
    {
        $userRepositoryStub = $this->createStub(UserRepository::class);

        $userRepositoryStub->method('getByEmail')->willThrowException(new UserNotFoundException());

        $createUserCommandHandler = new CreateUserCommandHandler(
            $userRepositoryStub,
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
        $user = new User(
            'Stanislav',
            'Rodikov',
            'example1000@example1000.com',
        );

        return [
            [
                new CreateEntityCommand($user),
                $user
            ]
        ];
    }
}
