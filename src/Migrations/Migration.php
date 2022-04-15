<?php

namespace App\Migrations;

use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;

abstract class Migration implements MigrationInterface
{
    /**
     * @param PdoConnectionDriver|null $connection
     */
    public function __construct(
        protected ?ConnectionInterface $connection = null
    )
    {
    }

    abstract function execute(): void;
}
