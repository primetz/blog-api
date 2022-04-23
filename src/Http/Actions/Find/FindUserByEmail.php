<?php

namespace App\Http\Actions\Find;

use App\Entities\User\UserInterface;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Queries\User\FindUserByEmailQuery;
use App\Queries\User\FindUserByEmailQueryHandler;

class FindUserByEmail implements ActionInterface
{
    private FindUserByEmailQueryHandler $findUserByEmailQueryHandler;

    public function __construct(
        ?FindUserByEmailQueryHandler $findUserByEmailQueryHandler = null
    )
    {
        $this->findUserByEmailQueryHandler = $findUserByEmailQueryHandler ?? new FindUserByEmailQueryHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $email = $request->query('email');

            /**
             * @var UserInterface $user
             */
            $user = $this->findUserByEmailQueryHandler->handle(
                new FindUserByEmailQuery($email)
            );
        } catch (HttpException|UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail()
        ]);
    }
}
