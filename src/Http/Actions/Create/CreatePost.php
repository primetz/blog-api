<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Entities\Post\Post;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreatePost implements ActionInterface
{
    private CreatePostCommandHandler $createPostCommandHandler;

    public function __construct(
        ?CreatePostCommandHandler $createPostCommandHandler = null
    )
    {
        $this->createPostCommandHandler = $createPostCommandHandler ?? new CreatePostCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $post = new Post(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );

            $this->createPostCommandHandler->handle(new CreateEntityCommand($post));
        } catch (HttpException $e) {
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
