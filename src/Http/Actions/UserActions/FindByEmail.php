<?php

namespace App\Http\Actions\UserActions;

use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;

class FindByEmail implements ActionInterface
{
    public function __construct(
        private ?UserRepositoryInterface $userRepository = null
    )
    {
        $this->userRepository = $this->userRepository ?? new UserRepository();
    }

    public function handle(Request $request): Response
    {
        try {
            $email = $request->query('email');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->userRepository->getByEmail($email);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail()
        ]);
    }
}
