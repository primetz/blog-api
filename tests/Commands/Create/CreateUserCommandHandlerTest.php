<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class CreateUserCommandHandlerTest extends TestCase
{
    use DummyConnectorTrait;

    /**
     * @dataProvider userDataProvider
     */
    public function testItThrowsAnUserEmailExistExceptionWhenUserAlreadyExists(
        User $user
    ): void
    {
        $userRepositoryStub = $this->createStub(UserRepository::class);

        $userRepositoryStub->method('getByEmail')->willReturn($user);

        $this->expectException(UserEmailExistException::class);

        $this->expectExceptionMessage(
            sprintf('User with email %s already exists', $user->getEmail())
        );

        $createUserCommandHandler = new CreateUserCommandHandler($userRepositoryStub);

        $createUserCommandHandler->handle(new CreateEntityCommand($user));
    }

    /**
     * @dataProvider userDataProvider
     * @throws UserEmailExistException
     */
    public function testItItSavesUserToDatabase(
        User $user
    ): void
    {
        $createUserCommandHandler = new CreateUserCommandHandler(
            new UserRepository(),
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
            ->with($createUserCommandHandler->getParams($user));

        $createUserCommandHandler->handle(new CreateEntityCommand($user));
    }

    #[Pure]
    public function userDataProvider(): iterable
    {
        return [
            [
                new User(
                    'Stanislav',
                    'Rodikov',
                    'example1000@example1000.com',
                )
            ]
        ];
    }
}
