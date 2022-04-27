<?php

namespace App\Migrations;

class Migration_comments_table_create_v_1 extends Migration implements MigrationInterface
{
    public function execute(): void
    {
        $this->connection->query(
            'CREATE TABLE comments (
                          id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                          author_id INTEGER NOT NULL,
                          post_id INTEGER NOT NULL,
                          text TEXT,
                          FOREIGN KEY (author_id) REFERENCES users(id),
                          FOREIGN KEY (post_id) REFERENCES posts(id)
                      );'
        );
    }
}
