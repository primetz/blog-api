<?php

namespace App\Commands;

use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;

abstract class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var PdoConnectionDriver|null $connection
     */

    public function __construct(
        protected ?ConnectionInterface $connection = null
    )
    {
    }

    public function getLastInsertId(?string $name = null): int|false
    {
        return $this->connection->lastInsertId($name) ? (int) $this->connection->lastInsertId($name) : false;
    }

    abstract public function handle(CommandInterface $command): void;

    abstract public function getSql(): string;

    abstract public function getParams(CommandInterface $command): array;
}
