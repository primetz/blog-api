<?php

namespace Tests\Repositories;

use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;

class UserRepositoryTest extends TestCase
{
    use DummyConnectorTrait;

    public function testItThrowsAnUserNotFoundExceptionWhenUserNotFoundById(): void
    {
        $this->PDOMock
            ->method('prepare')
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find user');

        $userRepository = new UserRepository($this->dummyConnector);

        $userRepository->get(1);
    }

    public function testItThrowsAnUserNotFoundExceptionWhenUserNotFoundByEmail(): void
    {
        $this->PDOMock
            ->method('prepare')
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find user');

        $userRepository = new UserRepository($this->dummyConnector);

        $userRepository->getByEmail('example@example.com');
    }

    /**
     * @dataProvider userProvider
     * @throws UserNotFoundException
     */
    public function testItReturnsUserById(
        array $inputValue,
        User $expectedUser
    ): void
    {
        $this->PDOMock
            ->method('prepare')
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->method('fetch')
            ->willReturn($inputValue);

        $userRepository = new UserRepository($this->dummyConnector);

        $user = $userRepository->get($expectedUser->getId());

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @dataProvider userProvider
     * @throws UserNotFoundException
     */
    public function testItReturnsUserByEmail(
        array $inputValue,
        User $expectedUser
    ): void
    {
        $this->PDOMock
            ->method('prepare')
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->method('fetch')
            ->willReturn($inputValue);

        $userRepository = new UserRepository($this->dummyConnector);

        $user = $userRepository->getByEmail($expectedUser->getEmail());

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @dataProvider userProvider
     */
    public function testItSavesUserToDatabase(
        array $inputValue,
        User $user,
        array $executeValue
    ): void
    {
        $this->PDOMock
            ->method('prepare')
            ->with(
                'INSERT INTO users (first_name, last_name, email) VALUES (:first_name, :last_name, :email)'
            )
            ->willReturn($this->PDOStatementMock);

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($executeValue);

        $userRepository = new UserRepository($this->dummyConnector);

        $userRepository->save($user);
    }

    #[Pure] public function userProvider(): iterable
    {
        $user = new User(
            'Stanislav',
            'Rodikov',
            'example@example.com',
            1
        );

        return [
            [
                [
                    'id' => $user->getId(),
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'email' => $user->getEmail(),
                ],
                $user,
                [
                    ':first_name' => $user->getFirstName(),
                    ':last_name' => $user->getLastName(),
                    ':email' => $user->getEmail(),
                ]
            ]
        ];
    }
}
