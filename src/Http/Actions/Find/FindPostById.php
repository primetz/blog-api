<?php

namespace App\Http\Actions\Find;

use App\Entities\Post\Post;
use App\Exceptions\HttpException;
use App\Exceptions\PostNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Queries\FindByIdQuery;
use App\Queries\Post\FindPostByIdQueryHandler;

class FindPostById implements ActionInterface
{
    private FindPostByIdQueryHandler $findPostByIdQueryHandler;

    public function __construct(
        ?FindPostByIdQueryHandler $findPostByIdQueryHandler = null
    )
    {
        $this->findPostByIdQueryHandler = $findPostByIdQueryHandler ?? new FindPostByIdQueryHandler();
    }

    public function handle(Request $request): Response
    {
        /**
         * @var Post $post
         */
        try {
            $id = $request->query('id');

            $post = $this->findPostByIdQueryHandler->handle(
                new FindByIdQuery($id)
            );
        } catch (HttpException|PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $post->getId(),
            'authorId' => $post->getAuthorId(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}
