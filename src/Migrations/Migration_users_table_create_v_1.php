<?php

namespace App\Migrations;

class Migration_users_table_create_v_1 extends Migration implements MigrationInterface
{
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
