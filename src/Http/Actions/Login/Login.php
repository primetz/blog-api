<?php

namespace App\Http\Actions\Login;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreateTokenCommandHandler;
use App\Entities\Token\AuthToken;
use App\Exceptions\AuthException;
use App\Exceptions\AuthTokensRepositoryException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\AuthTokenRepository\AuthTokensRepositoryInterface;
use DateTimeImmutable;
use Exception;

class Login implements ActionInterface
{
    public function __construct(
        private readonly PasswordAuthenticationInterface $authentication,
        private readonly AuthTokensRepositoryInterface $authTokensRepository,
        private readonly CreateTokenCommandHandler $createTokenCommandHandler
    )
    {
    }

    /**
     * @throws AuthTokensRepositoryException
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        try {
            $user = $this->authentication->getUser($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        if (!$authToken = $this->authTokensRepository->getTokenByUser($user)) {
            $authToken = new AuthToken(
                bin2hex(random_bytes(40)),
                $user,
                (new DateTimeImmutable())->modify('+1 day')
            );
        }

        $this->createTokenCommandHandler->handle(new CreateEntityCommand($authToken));

        return new SuccessfulResponse([
            'token' => $authToken->getToken()
        ]);
    }
}
