<?php

namespace App\Traits\Instance;

trait Instance
{
    protected static array $instances = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}
