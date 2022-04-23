<?php

namespace App\Http\Actions\Delete;

use App\Commands\Delete\DeleteEntityCommand;
use App\Commands\Delete\DeletePostCommandHandler;
use App\Exceptions\HttpException;
use App\Exceptions\PostNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class DeletePost implements ActionInterface
{
    private DeletePostCommandHandler $deletePostCommandHandler;

    public function __construct(
        ?DeletePostCommandHandler $deletePostCommandHandler = null
    )
    {
        $this->deletePostCommandHandler = $deletePostCommandHandler ?? new DeletePostCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');

            $this->deletePostCommandHandler->handle(new DeleteEntityCommand($id));
        } catch (HttpException|PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $id
        ]);
    }
}
