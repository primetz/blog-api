<?php

namespace Tests;

use App\Connections\ConnectorInterface;
use App\Drivers\ConnectionInterface;

class DummyConnector implements ConnectorInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
