<?php

namespace App\Commands\SymfonyCommands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Commands\Create\CreateUserCommandHandler;
use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistsException;
use App\Exceptions\UserNotFoundException;
use App\Faker\Faker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private readonly Faker $faker,
        private readonly CreateUserCommandHandler $createUserCommandHandler,
        private readonly CreatePostCommandHandler $createPostCommandHandler,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $users = [];

        /**
         * @var User $user
         */
        for ($i = 0; $i < 10; $i++) {
            try {
                $user = $this->createFakeUser();
            } catch (UserEmailExistsException|UserNotFoundException $e) {
                $output->writeln($e->getMessage());

                continue;
            }

            $users[] = $user;

            $output->writeln(
                sprintf('User created: %s', $user->getEmail())
            );
        }

        /**
         * @var Post $post
         */
        foreach ($users as $user) {
            for ($i = 0; $i < 20; $i++) {
                $post = $this->createFakePost($user);

                $output->writeln(
                    sprintf('Post created: %s', $post->getTitle())
                );
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @throws UserEmailExistsException
     * @throws UserNotFoundException
     */
    private function createFakeUser(): EntityInterface
    {
        $user = new User(
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->unique()->email,
            $this->faker->password
        );

        return $this->createUserCommandHandler->handle(new CreateEntityCommand($user));
    }

    private function createFakePost(EntityInterface $author): EntityInterface
    {
        $post = new Post(
            $author->getId(),
            $this->faker->sentence,
            $this->faker->realText
        );

        return $this->createPostCommandHandler->handle(new CreateEntityCommand($post));
    }
}
