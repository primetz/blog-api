<?php

namespace App\Http\Actions\UserActions;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\HttpException;
use App\Exceptions\UserEmailExistException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateUser implements ActionInterface
{

    public function __construct(
        private ?CreateUserCommandHandler $createUserCommandHandler = null
    )
    {
        $this->createUserCommandHandler = $this->createUserCommandHandler ?? new CreateUserCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $user = new User(
                $request->jsonBodyField('firstName'),
                $request->jsonBodyField('lastName'),
                $request->jsonBodyField('email')
            );

            $this->createUserCommandHandler->handle(new CreateEntityCommand($user));
        } catch (HttpException|UserEmailExistException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'email' => $user->getEmail()
        ]);
    }
}
