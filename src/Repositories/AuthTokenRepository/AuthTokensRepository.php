<?php

namespace App\Repositories\AuthTokenRepository;

use App\Entities\EntityInterface;
use App\Entities\Token\AuthTokenInterface;
use App\Exceptions\AuthTokenNotFoundException;
use App\Queries\Token\TokenQueryHandlerInterface;

class AuthTokensRepository implements AuthTokensRepositoryInterface
{
    private array $tokens;

    public function __construct(
        private readonly TokenQueryHandlerInterface $tokenQueryHandler
    )
    {
        $this->tokens = $this->tokenQueryHandler->handle();
    }

    /**
     * @throws AuthTokenNotFoundException
     */
    public function getToken(string $token): ?AuthTokenInterface
    {
        if (!isset($this->tokens[$token])) {
            throw new AuthTokenNotFoundException(
                sprintf('Token not found: [%s]', $token)
            );
        }

        return $this->tokens[$token];
    }

    public function getTokenByUser(EntityInterface $user): ?AuthTokenInterface
    {
        $userToken = null;

        foreach ($this->tokens as $token) {
            if ($user->getId() === $token->getUser()->getId() && !$token->isExpires()) {
                $userToken = $token;

                break;
            }
        }

        return $userToken;
    }
}
