<?php

namespace App\Commands;

use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;
use App\Entities\EntityInterface;

abstract class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var PdoConnectionDriver $connection
     */

    public function __construct(
        protected ConnectionInterface $connection
    )
    {
    }

    public function getLastInsertId(?string $name = null): int|false
    {
        return $this->connection->lastInsertId($name) ? (int) $this->connection->lastInsertId($name) : false;
    }

//    abstract public function handle(CommandInterface $command): EntityInterface;

    abstract public function getSql(): string;

    abstract public function getParams(CommandInterface $command): array;
}
