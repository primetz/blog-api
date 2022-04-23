<?php

namespace App\Queries\Token;

use App\Drivers\ConnectionInterface;
use App\Entities\Token\AuthToken;
use App\Exceptions\AuthTokensRepositoryException;
use App\Repositories\UserRepository\UserRepositoryInterface;
use DateTimeImmutable;
use Exception;
use PDO;

class TokenQueryHandler implements TokenQueryHandlerInterface
{
    public function __construct(
        private ConnectionInterface $connection,
        private UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @throws AuthTokensRepositoryException
     */
    public function handle(): array
    {
        try {
            $statement = $this->connection->prepare($this->getSql());

            $statement->execute();

            $tokensData = $statement->fetchAll(PDO::FETCH_OBJ);

            try {
                $tokens = [];

                foreach ($tokensData as $tokenData) {
                    $tokens[$tokenData->token] = new AuthToken(
                        $tokenData->token,
                        $this->userRepository->get($tokenData->user_id),
                        new DateTimeImmutable($tokenData->expires_on)
                    );
                }

                return $tokens;
            } catch (Exception $e) {
                throw new AuthTokensRepositoryException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e
                );
            }
        } catch (\PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function getSql(): string
    {
        return 'SELECT * FROM tokens';
    }
}
