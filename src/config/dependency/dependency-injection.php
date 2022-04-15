<?php

use App\config\SqliteConfig;
use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;
use App\Repositories\CommentRepository\CommentRepository;
use App\Repositories\CommentRepository\CommentRepositoryInterface;
use App\Repositories\PostRepository\PostRepository;
use App\Repositories\PostRepository\PostRepositoryInterface;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;

return [
    UserRepositoryInterface::class => UserRepository::class,
    PostRepositoryInterface::class => PostRepository::class,
    CommentRepositoryInterface::class => CommentRepository::class,
    ConnectionInterface::class => PdoConnectionDriver::getInstance(SqliteConfig::DSN),
];
