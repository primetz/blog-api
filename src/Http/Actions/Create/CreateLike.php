<?php

namespace App\Http\Actions\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateLikeCommandHandler;
use App\Entities\Like\Like;
use App\Exceptions\HttpException;
use App\Exceptions\LikeExistsException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateLike implements ActionInterface
{
    private CreateLikeCommandHandler $createLikeCommandHandler;

    public function __construct(
        ?CreateLikeCommandHandler $createLikeCommandHandler = null
    )
    {
        $this->createLikeCommandHandler = $createLikeCommandHandler ?? new  CreateLikeCommandHandler();
    }

    public function handle(Request $request): Response
    {
        try {
            $like = new Like(
                $request->jsonBodyField('userId'),
                $request->jsonBodyField('postId')
            );

            $this->createLikeCommandHandler->handle(new CreateEntityCommand($like));
        } catch (HttpException|LikeExistsException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => $this->createLikeCommandHandler->getLastInsertId(),
            'userId' => $like->getUserId(),
            'postId' => $like->getPostId()
        ]);
    }
}
