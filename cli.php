<?php

use App\Commands\Create\CreateCommentCommandHandler;
use App\Commands\Create\CreateEntityCommand;
use App\Commands\Create\CreatePostCommandHandler;
use App\Commands\Create\CreateUserCommandHandler;
use App\Commands\CreateCommand;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory\EntityManagerFactory;
use App\Factories\EntityManagerFactory\EntityManagerFactoryInterface;

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

    $commandHandler = new CreateUserCommandHandler();

    $commandHandler->handle(
        new CreateEntityCommand($entityManger->createEntityByInputArguments($argv))
    );

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
