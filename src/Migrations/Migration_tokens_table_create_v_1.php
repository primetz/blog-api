<?php

namespace App\Migrations;

class Migration_tokens_table_create_v_1 extends Migration implements MigrationInterface
{

    function execute(): void
    {
        $this->connection->query(
            'CREATE TABLE tokens (
                          token TEXT NOT NULL PRIMARY KEY,
                          user_id INTEGER NOT NULL,
                          expires_on TEXT NOT NULL,
                          FOREIGN KEY (user_id) REFERENCES users(id)
                      );'
        );
    }
}
