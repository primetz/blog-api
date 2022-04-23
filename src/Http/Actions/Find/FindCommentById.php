<?php

namespace App\Http\Actions\Find;

use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Queries\Comment\FindCommentByIdQueryHandler;
use App\Queries\FindByIdQuery;

class FindCommentById implements ActionInterface
{
    private FindCommentByIdQueryHandler $findCommentByIdQueryHandler;

    public function __construct(
        ?FindCommentByIdQueryHandler $findCommentByIdQueryHandler = null
    )
    {
        $this->findCommentByIdQueryHandler = $findCommentByIdQueryHandler ?? new FindCommentByIdQueryHandler();
    }

    public function handle(Request $request): Response
    {
        /**
         * @var Comment $comment
         */
        try {
            $id = $request->query('id');

            $comment = $this->findCommentByIdQueryHandler->handle(
                new FindByIdQuery($id)
            );
        } catch (HttpException|CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $comment->getId(),
            'authorId' => $comment->getAuthorId(),
            'postId' => $comment->getPostId(),
            'text' => $comment->getText()
        ]);
    }
}
