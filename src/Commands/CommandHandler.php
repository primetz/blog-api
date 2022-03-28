<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use PDOStatement;

abstract class CommandHandler implements CommandHandlerInterface
{
    protected PDOStatement|false $statement;

    private ?ConnectorInterface $connector;

    public function __construct(
        ConnectorInterface $connector = null,
    )
    {
        $this->connector = $connector ?? new SqlLiteConnector();

        $this->statement = $this->connector->getConnection()->prepare($this->getSql());
    }

    abstract protected function getSql(): string;

    abstract public function handle(CommandInterface $command): void;
}
