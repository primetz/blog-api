<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\HttpException;
use App\Exceptions\UserEmailExistsException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateUser implements ActionInterface
{
    public function __construct(
        private readonly CreateUserCommandHandler $createUserCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $user = new User(
                $request->jsonBodyField('firstName'),
                $request->jsonBodyField('lastName'),
                $request->jsonBodyField('email'),
                $request->jsonBodyField('password')
            );

            $this->createUserCommandHandler->handle(new CreateEntityCommand($user));
        } catch (HttpException|UserEmailExistsException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $this->createUserCommandHandler->getLastInsertId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail()
        ]);
    }
}
