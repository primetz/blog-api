<?php

namespace App\Commands\Delete;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Connections\ConnectorInterface;
use App\Exceptions\PostNotFoundException;
use App\Repositories\PostRepository\PostRepository;
use App\Repositories\PostRepository\PostRepositoryInterface;

final class DeletePostCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    private PostRepositoryInterface $postRepository;

    public function __construct(
        PostRepositoryInterface $postRepository = null,
        ?ConnectorInterface $connector = null
    )
    {
        $this->postRepository = $postRepository ?? new PostRepository();

        parent::__construct($connector);
    }

    /**
     * @param DeleteEntityCommand $command
     * @throws PostNotFoundException
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
