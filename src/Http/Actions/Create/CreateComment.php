<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateComment implements ActionInterface
{
    public function __construct(
        private readonly TokenAuthenticationInterface $authentication,
        private readonly CreateCommentCommandHandler $createCommentCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->getUser($request);

            $comment = new Comment(
                $author->getId(),
                $request->jsonBodyField('postId'),
                $request->jsonBodyField('text')
            );

            $this->createCommentCommandHandler->handle(new  CreateEntityCommand($comment));
        } catch (HttpException|AuthException $e) {
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
