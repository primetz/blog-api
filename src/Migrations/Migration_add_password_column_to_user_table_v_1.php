<?php

namespace App\Migrations;

class Migration_add_password_column_to_user_table_v_1 extends Migration implements MigrationInterface
{

    function execute(): void
    {
        $this->connection->query(
            'ALTER TABLE users ADD COLUMN password TEXT'
        );
    }
}
