<?php

namespace App\Commands\SymfonyCommands\Create;

use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\PostNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\PostRepository\PostRepositoryInterface;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateComment extends Command
{
    public function __construct(
        private readonly CreateCommentCommandHandler $createCommentCommandHandler,
        private readonly UserRepositoryInterface $userRepository,
        private readonly PostRepositoryInterface $postRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('create:comment')
            ->setDescription('Creates new comment')
            ->addArgument('authorId', InputArgument::REQUIRED, 'Author id')
            ->addArgument('postId', InputArgument::REQUIRED, 'Post id')
            ->addArgument('text', InputArgument::REQUIRED, 'Post text');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $output->writeln('Create comment command started');

        $comment = new Comment(
            $input->getArgument('authorId'),
            $input->getArgument('postId'),
            $input->getArgument('text')
        );

        try {
            $this->userRepository->get($comment->getAuthorId());

            $this->postRepository->get($comment->getPostId());

            $this->createCommentCommandHandler->handle(new CreateEntityCommand($comment));
        } catch (UserNotFoundException|PostNotFoundException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln(
            sprintf('Comment created: %s', $comment->getText())
        );

        return Command::SUCCESS;
    }
}
