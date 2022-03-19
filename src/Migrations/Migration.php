<?php

namespace App\Migrations;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use JetBrains\PhpStorm\Pure;

abstract class Migration implements MigrationInterface
{
    protected ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    abstract function execute(): void;
}
