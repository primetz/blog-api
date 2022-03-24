<?php

namespace App\Connections;

use App\Drivers\ConnectionInterface;
use App\Drivers\PdoConnectionDriver\PdoConnectionDriver;
use PDOException;

abstract class Connector implements ConnectorInterface
{
    public function getConnection(): ConnectionInterface
    {
        try {
            $connection = PdoConnectionDriver::getInstance(
                $this->getDsn(),
                $this->getUserName(),
                $this->getPassword(),
                $this->getOptions()
            );
        } catch (PDOException $e) {
            print $e->getMessage();
            die();
        }

        return $connection;
    }

    abstract public function getDsn(): string;

    abstract public function getUserName(): string;

    abstract public function getPassword(): string;

    abstract public function getOptions(): array;
}
