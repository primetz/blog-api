<?php

use Symfony\Component\Console\Application;


$container = require_once __DIR__ . '/container.php';

$commands = require_once __DIR__ . '/src/config/commands/symfonyCommands.php';

$app = new Application();

foreach ($commands as $command) {
    $app->add($container->get($command));
}

try {
    $app->run();
} catch (Exception $e) {
    print $e->getMessage();
}
