<?php

namespace App\Queries;

class FindByIdQuery implements QueryInterface
{
    public function __construct(
        private int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
