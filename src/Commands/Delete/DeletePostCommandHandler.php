<?php

namespace App\Commands\Delete;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Repositories\PostRepository\PostRepositoryInterface;

final class DeletePostCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ?PostRepositoryInterface $postRepository = null,
        ?ConnectionInterface $connection = null
    )
    {
        parent::__construct($connection);
    }

    /**
     * @param DeleteEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->postRepository->get($command->getId());

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    public function getSql(): string
    {
        return 'DELETE FROM posts WHERE id = :id';
    }

    /**
     * @param DeleteEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        return [
            ':id' => $command->getId()
        ];
    }
}
