<?php


use App\Container\DIContainer;

$dependencies = require_once __DIR__ . '/src/config/dependency/dependency-injection.php';

$container = DIContainer::getInstance();

foreach ($dependencies as $key => $value) {
    $container->bind($key, $value);
}

return $container;
