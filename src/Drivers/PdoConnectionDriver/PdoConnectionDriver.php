<?php

namespace App\Drivers\PdoConnectionDriver;

use App\Drivers\ConnectionInterface;
use PDO;

class PdoConnectionDriver extends PDO implements ConnectionInterface
{
    protected static array $instances = [];

    public function __construct(
        string $dsn,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null
    )
    {
        parent::__construct($dsn, $username, $password, $options);
    }

    public static function getInstance(
        string $dsn,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null
    )
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static(
                $dsn,
                $username,
                $password,
                $options
            );
        }

        return self::$instances[$class];
    }

    public function executeQuery(string $query, array $params)
    {
        $this->prepare($query)->execute($params);
    }
}
