<?php

namespace App\Http\Actions\Logout;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateTokenCommandHandler;
use App\Exceptions\AuthException;
use App\Exceptions\AuthTokensRepositoryException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\AuthTokenRepository\AuthTokensRepositoryInterface;

class Logout implements ActionInterface
{
    public function __construct(
        private readonly TokenAuthenticationInterface $authentication,
        private readonly AuthTokensRepositoryInterface $authTokensRepository,
        private readonly CreateTokenCommandHandler $createTokenCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->authentication->getUser($request);

            $authToken = $this->authTokensRepository->getTokenByUser($user);

            $authToken->expire();

            $this->createTokenCommandHandler->handle(new CreateEntityCommand($authToken));
        } catch (AuthException|AuthTokensRepositoryException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse();
    }
}
