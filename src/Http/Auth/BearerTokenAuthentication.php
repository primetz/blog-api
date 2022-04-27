<?php

namespace App\Http\Auth;

use App\Entities\EntityInterface;
use App\Enums\AuthToken;
use App\Exceptions\AuthException;
use App\Exceptions\AuthTokenNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Request;
use App\Repositories\AuthTokenRepository\AuthTokensRepositoryInterface;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
    public function __construct(
        private readonly AuthTokensRepositoryInterface $authTokensRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function getUser(Request $request): EntityInterface
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!str_starts_with($header, AuthToken::HEADER_PREFIX->value)) {
            throw new AuthException(
                sprintf('Malformed token: [%s]', $header)
            );
        }

        $token = mb_substr($header, strlen(AuthToken::HEADER_PREFIX->value));

        try {
            $authToken = $this->authTokensRepository->getToken($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException(
                sprintf('Bad token: [%s]', $token)
            );
        }

        if ($authToken->isExpires()) {
            throw new AuthException(
                sprintf('Token expired: [%s]', $token)
            );
        }

        return $authToken->getUser();
    }
}
