<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Entities\Token\AuthToken;
use App\Exceptions\AuthTokensRepositoryException;
use DateTimeInterface;
use PDOException;

class CreateTokenCommandHandler extends CommandHandler implements CommandHandlerInterface
{

    /**
     * @param CreateEntityCommand $command
     * @throws AuthTokensRepositoryException
     */
    public function handle(CommandInterface $command): void
    {
        try {
            $this->connection->executeQuery(
                $this->getSql(),
                $this->getParams($command)
            );
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param CreateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var AuthToken $authToken
         */
        $authToken = $command->getEntity();

        return [
            ':token' => $authToken->getToken(),
            ':user_id' => $authToken->getUser()->getId(),
            ':expires_on' => $authToken->getExpiresOn()->format(DateTimeInterface::ATOM)
        ];
    }

    public function getSql(): string
    {
    return 'INSERT INTO tokens (token, user_id, expires_on)
                VALUES (:token, :user_id, :expires_on)
                ON CONFLICT (token) DO UPDATE SET expires_on = :expires_on';
    }
}
