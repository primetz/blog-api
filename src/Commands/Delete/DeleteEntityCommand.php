<?php

namespace App\Commands\Delete;

use App\Commands\CommandInterface;

class DeleteEntityCommand implements CommandInterface
{
    public function __construct(
        private int $id,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
