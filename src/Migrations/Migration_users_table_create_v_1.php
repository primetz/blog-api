<?php

namespace App\Migrations;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector\SqlLiteConnector;
use JetBrains\PhpStorm\Pure;

class Migration_users_table_create_v_1 implements MigrationInterface
{
    private ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    public function execute(): void
    {
        $this->connector->getConnection()->query(
            'CREATE TABLE users (
                          id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                          first_name VARCHAR(100),
                          last_name VARCHAR(100),
                          email VARCHAR(255) UNIQUE NOT NULL 
                      );'
        );
    }
}
