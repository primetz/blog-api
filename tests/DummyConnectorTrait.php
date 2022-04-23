<?php

namespace Tests;

use App\Connections\ConnectorInterface;
use App\Drivers\ConnectionInterface;
use PDO;
use PDOStatement;

trait DummyConnectorTrait
{
    private ?PDO $PDOMock;

    private ?PDOStatement $PDOStatementMock;

    private ?ConnectionInterface $connection;

    private ?ConnectorInterface $dummyConnector;

    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        ?PDO $PDOMock = null,
        ?PDOStatement $PDOStatementMock = null,
        ?ConnectionInterface $connection = null,
        ?ConnectorInterface $dummyConnector = null,
    )
    {
        parent::__construct($name, $data, $dataName);

        $this->PDOMock = $PDOMock ?? $this->createMock(PDO::class);

        $this->PDOStatementMock = $PDOStatementMock ?? $this->createMock(PDOStatement::class);

        $this->connection = $connection ?? new DummyConnection($this->PDOMock);

        $this->dummyConnector = $dummyConnector ?? new DummyConnector($this->connection);
    }
}
