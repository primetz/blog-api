<?php

namespace App\Commands\Create;

use App\Commands\CommandHandler;
use App\Commands\CommandHandlerInterface;
use App\Commands\CommandInterface;
use App\Drivers\ConnectionInterface;
use App\Entities\Like\Like;
use App\Entities\Like\LikeInterface;
use App\Exceptions\LikeExistsException;
use App\Exceptions\LikeNotFoundException;
use App\Repositories\LikeRepository\LikeRepositoryInterface;

final class CreateLikeCommandHandler extends CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ?LikeRepositoryInterface $likeRepository = null,
        ?ConnectionInterface $connection = null
    )
    {
        parent::__construct($connection);
    }

    /**
     * @param CreateEntityCommand $command
     * @throws LikeExistsException
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var LikeInterface $like
         */
        $like = $command->getEntity();

        if ($this->isLikeExists($like->getUserId(), $like->getPostId())) {
            throw new LikeExistsException('You can\'t like more than one post');
        }

        $this->connection->executeQuery(
            $this->getSql(),
            $this->getParams($command)
        );
    }

    private function isLikeExists(int $userId, int $postId): bool
    {
        try {
            $this->likeRepository->getByUserIdAndPostId($userId, $postId);
        } catch (LikeNotFoundException) {
            return false;
        }

        return true;
    }

    public function getSql(): string
    {
        return 'INSERT INTO likes (user_id, post_id) VALUES (:userId, :postId)';
    }

    /**
     * @param CreateEntityCommand $command
     */
    public function getParams(CommandInterface $command): array
    {
        /**
         * @var Like $like
         */
        $like = $command->getEntity();

        return [
            ':userId' => $like->getUserId(),
            ':postId' => $like->getPostId(),
        ];
    }
}
