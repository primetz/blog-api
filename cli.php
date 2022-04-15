<?php

use App\Commands\CommandHandlerInterface;
use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Commands\Create\CreateUserCommandHandler;
use App\Commands\CreateCommand;
use App\Container\DIContainer;
use App\Entities\Comment\Comment;
use App\Entities\Post\Post;
use App\Entities\User\User;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory\EntityManagerFactory;
use App\Factories\EntityManagerFactory\EntityManagerFactoryInterface;

$container = require_once __DIR__ . '/container.php';

try {
    if(count($argv) < 2)
    {
        throw new NotFoundException('404');
    }
    // TODO это условие мешает выводить информативное исключение из App\Factories\EntityFactory
//    if(!in_array($argv[1], Argument::getArgumentValues()))
//    {
//        throw new NotFoundException('404');
//    }

    /**
     * @var EntityManagerFactoryInterface $entityManger
     */
    $entityManger = EntityManagerFactory::getInstance();

    $entity = $entityManger->createEntityByInputArguments($argv);

    /**
     * @var DIContainer $container
     */
    if (isset($container)) {
        /**
         * @var CommandHandlerInterface $commandHandler
         */
        $commandHandler = match ($entity::class) {
            User::class => $container->get(CreateUserCommandHandler::class),
            Post::class => $container->get(CreatePostCommandHandler::class),
            Comment::class => $container->get(CreateCommentCommandHandler::class)
        };

        $commandHandler->handle(new CreateEntityCommand($entity));
    }

//    $command = new CreateCommand($entityManger->getRepositoryByInputArguments($argv));
//    $command->handle($entityManger->createEntityByInputArguments($argv));
    //про это расскажу на следущей лекции, это тоже паттерн команда, основная мысль,
    // заворачивать запросы или простые операции в отдельные объекты. то есть в команде у нас выполняется какое-нибудь действией с базой,
    // которое мы можем вызывать в любой момент, например мне нужно создать 3 миллиона юзеров на портале я создаю очередь которая будет каждую итерацию
    // создавать юзера, команды часто используются кроном(программа которая запускается в указанное ей время).

}catch (Exception $exception)
{
    echo $exception->getMessage().PHP_EOL;
    http_response_code(404);
}
