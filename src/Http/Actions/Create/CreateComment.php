<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateComment implements ActionInterface
{
    private CreateCommentCommandHandler $createCommentCommandHandler;

    public function __construct(
        CreateCommentCommandHandler $createCommentCommandHandler = null
    )
    {
        $this->createCommentCommandHandler = $createCommentCommandHandler ?? new CreateCommentCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $comment = new Comment(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('postId'),
                $request->jsonBodyField('text')
            );

            $this->createCommentCommandHandler->handle(new  CreateEntityCommand($comment));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $this->createCommentCommandHandler->getLastInsertId(),
            'authorId' => $comment->getAuthorId(),
            'postId' => $comment->getPostId(),
            'text' => $comment->getText()
        ]);
    }
}
