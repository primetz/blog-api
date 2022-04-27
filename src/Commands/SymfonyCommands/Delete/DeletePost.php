<?php

namespace App\Commands\SymfonyCommands\Delete;

use App\Commands\Delete\DeleteEntityCommand;
use App\Commands\Delete\DeletePostCommandHandler;
use App\Exceptions\PostNotFoundException;
use App\Repositories\PostRepository\PostRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly DeletePostCommandHandler $deletePostCommandHandler
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('delete:post')
            ->setDescription('Deletes a post')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of a post to delete')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Do not ask for consent to delete');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $question = new ConfirmationQuestion(
            'Delete post [Y/n]? ',
            false
        );

        if (!$input->getOption('force') && !$this->getHelper('question')->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        try {
            $post = $this->postRepository->get($input->getArgument('id'));

            $postId = $post->getId();

            $this->deletePostCommandHandler->handle(new DeleteEntityCommand($postId));
        } catch (PostNotFoundException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln(
            sprintf('Post %d deleted', $postId)
        );

        return Command::SUCCESS;
    }
}
