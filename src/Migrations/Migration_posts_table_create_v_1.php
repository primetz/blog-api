<?php

namespace App\Migrations;

class Migration_posts_table_create_v_1 extends Migration implements MigrationInterface
{
    public function execute(): void
    {
        $this->connection->query(
            'CREATE TABLE posts (
                          id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                          author_id INTEGER NOT NULL,
                          title VARCHAR(255),
                          text TEXT,
                          FOREIGN KEY (author_id) REFERENCES users(id)
                      );'
        );
    }
}
