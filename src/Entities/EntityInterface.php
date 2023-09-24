<?php

namespace App\Entities;

interface EntityInterface
{
    public function __toString(): string;

    public function getId(): ?int;
}
