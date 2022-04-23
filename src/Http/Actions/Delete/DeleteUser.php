<?php

namespace App\Http\Actions\Delete;

use App\Commands\Delete\DeleteEntityCommand;
use App\Commands\Delete\DeleteUserCommandHandler;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class DeleteUser implements ActionInterface
{
    private DeleteUserCommandHandler $deleteUserCommandHandler;

    public function __construct(
        ?DeleteUserCommandHandler $deleteUserCommandHandler = null
    )
    {
        $this->deleteUserCommandHandler = $deleteUserCommandHandler ?? new DeleteUserCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');

            $this->deleteUserCommandHandler->handle(new DeleteEntityCommand($id));
        } catch (HttpException|UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $id
        ]);
    }
}
