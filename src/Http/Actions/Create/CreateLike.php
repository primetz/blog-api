<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateLikeCommandHandler;
use App\Entities\Like\Like;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Exceptions\LikeExistsException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateLike implements ActionInterface
{
    public function __construct(
        private readonly TokenAuthenticationInterface $authentication,
        private readonly CreateLikeCommandHandler $createLikeCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->getUser($request);

            $like = new Like(
                $author->getId(),
                $request->jsonBodyField('postId')
            );

            $this->createLikeCommandHandler->handle(new CreateEntityCommand($like));
        } catch (HttpException|LikeExistsException|AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $this->createLikeCommandHandler->getLastInsertId(),
            'userId' => $like->getUserId(),
            'postId' => $like->getPostId()
        ]);
    }
}
