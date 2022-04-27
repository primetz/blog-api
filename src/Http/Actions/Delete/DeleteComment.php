<?php

namespace App\Http\Actions\Delete;

use App\Commands\Delete\DeleteCommentCommandHandler;
use App\Commands\Delete\DeleteEntityCommand;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class DeleteComment implements ActionInterface
{
    private DeleteCommentCommandHandler $deleteCommentCommandHandler;

    public function __construct(
        ?DeleteCommentCommandHandler $deleteCommentCommandHandler = null
    )
    {
        $this->deleteCommentCommandHandler = $deleteCommentCommandHandler ?? new DeleteCommentCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');

            $this->deleteCommentCommandHandler->handle(new DeleteEntityCommand($id));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $id
        ]);
    }
}
