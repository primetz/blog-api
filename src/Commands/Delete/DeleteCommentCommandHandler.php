<?php

namespace App\Commands\Delete;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Connections\ConnectorInterface;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepository\CommentRepository;
use App\Repositories\CommentRepository\CommentRepositoryInterface;

class DeleteCommentCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    private CommentRepositoryInterface $commentRepository;
    public function __construct(
        ?CommentRepositoryInterface $commentRepository = null,
        ?ConnectorInterface $connector = null
    )
    {
        $this->commentRepository = $commentRepository ?? new CommentRepository();

        parent::__construct($connector);
    }

    /**
     * @param DeleteEntityCommand $command
     * @throws CommentNotFoundException
     */
    public function handle(CommandInterface $command): void
    {
        $this->commentRepository->get($command->getId());

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    public function getSql(): string
    {
        return 'DELETE FROM comments WHERE id = :id';
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
