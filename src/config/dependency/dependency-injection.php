<?php

use App\config\SqliteConfig;
use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;
use App\Http\Auth\BearerTokenAuthentication;
use App\Http\Auth\PasswordAuthentication;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Queries\Token\TokenQueryHandler;
use App\Queries\Token\TokenQueryHandlerInterface;
use App\Repositories\AuthTokenRepository\AuthTokensRepository;
use App\Repositories\AuthTokenRepository\AuthTokensRepositoryInterface;
use App\Repositories\CommentRepository\CommentRepository;
use App\Repositories\CommentRepository\CommentRepositoryInterface;
use App\Repositories\LikeRepository\LikeRepository;
use App\Repositories\LikeRepository\LikeRepositoryInterface;
use App\Repositories\PostRepository\PostRepository;
use App\Repositories\PostRepository\PostRepositoryInterface;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

return [
    UserRepositoryInterface::class => UserRepository::class,
    PostRepositoryInterface::class => PostRepository::class,
    CommentRepositoryInterface::class => CommentRepository::class,
    LikeRepositoryInterface::class => LikeRepository::class,
    ConnectionInterface::class => PdoConnectionDriver::getInstance(SqliteConfig::DSN),
    LoggerInterface::class => (new Logger('blog'))
        ->pushHandler(
            new StreamHandler(dirname(__DIR__, 3) . '/.logs/blog.log')
        )
        ->pushHandler(
            new StreamHandler(
                dirname(__DIR__, 3) . '/.logs/blog.warning.log',
                Logger::WARNING,
                false
            )
        ),
    PasswordAuthenticationInterface::class => PasswordAuthentication::class,
    TokenAuthenticationInterface::class => BearerTokenAuthentication::class,
    AuthTokensRepositoryInterface::class => AuthTokensRepository::class,
    TokenQueryHandlerInterface::class => TokenQueryHandler::class
];
