<?php

namespace App\Connections\SqlLiteConnector;

use App\config\SqliteConfig;
use App\Connections\Connector;

class SqlLiteConnector extends Connector implements SqlLiteConnectorInterface
{

    public function getDsn(): string
    {
        return SqliteConfig::DSN;
    }

    public function getUserName(): string
    {
        return '';
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getOptions(): array
    {
        return [];
    }
}
