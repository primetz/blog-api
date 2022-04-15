<?php

namespace App\Migrations;

class Migration_likes_table_create_v_1 extends Migration implements MigrationInterface
{

    function execute(): void
    {
        $this->connection->query(
            'CREATE TABLE likes (
                          id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                          user_id INTEGER NOT NULL,
                          post_id INTEGER NOT NULL,
                          FOREIGN KEY (user_id) REFERENCES users(id),
                          FOREIGN KEY (post_id) REFERENCES posts(id),
                          CONSTRAINT uq_user_id_post_id UNIQUE (user_id, post_id)
                      );'
        );
    }
}
