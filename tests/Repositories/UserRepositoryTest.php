<?php

namespace Tests\Repositories;

use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository\UserRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Tests\DummyConnectorTrait;
use Tests\DummyLogger;

class UserRepositoryTest extends TestCase
{
    use DummyConnectorTrait;

    public function testItThrowsAnUserNotFoundExceptionWhenUserNotFoundById(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find user');

        $userRepository = new UserRepository(
            $this->connection,
            new DummyLogger()
        );

        $userRepository->get(1);
    }

    public function testItThrowsAnUserNotFoundExceptionWhenUserNotFoundByEmail(): void
    {
        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Can\'t find user');

        $userRepository = new UserRepository(
            $this->connection,
            new DummyLogger()
        );

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
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM users WHERE id = :id'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $userRepository = new UserRepository(
            $this->connection
        );

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
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM users WHERE email = :email'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturn($inputValue);

        $userRepository = new UserRepository(
            $this->connection
        );

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
            ->expects($this->once())
            ->method('prepare')
            ->with(
                'INSERT INTO users (first_name, last_name, email) VALUES (:first_name, :last_name, :email)'
            );

        $this->PDOStatementMock
            ->expects($this->once())
            ->method('execute')
            ->with($executeValue);

        $userRepository = new UserRepository(
            $this->connection,
            new DummyLogger()
        );

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
