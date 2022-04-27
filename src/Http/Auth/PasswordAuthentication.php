<?php

namespace App\Http\Auth;

use App\Entities\User\UserInterface;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Request;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function getUser(Request $request): UserInterface
    {
        try {
            $email = $request->jsonBodyField('email');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {

            $user = $this->userRepository->getByEmail($email);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!$user->verifyPassword($password, $user->getPassword())) {
            throw new AuthException('Wrong password');
        }

        return $user;
    }
}
