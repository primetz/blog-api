<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Entities\Post\Post;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
    public function __construct(
        private readonly TokenAuthenticationInterface $authentication,
        private readonly CreatePostCommandHandler $createPostCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->getUser($request);

            $post = new Post(
                $author->getId(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );

            $this->createPostCommandHandler->handle(new CreateEntityCommand($post));
        } catch (HttpException|AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $this->createPostCommandHandler->getLastInsertId(),
            'authorId' => $post->getAuthorId(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}
