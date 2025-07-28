<?php
namespace App\Core;

class Singleton
{
    private static array $instances = [];

    protected function __construct() {}

    public static function getInstance(): static
    {
        $calledClass = get_called_class();
        if (!isset(self::$instances[$calledClass])) {
            $reflection = new \ReflectionClass($calledClass);
            self::$instances[$calledClass] = $reflection->newInstanceWithoutConstructor();
           
            if ($reflection->hasMethod('__construct')) 
            {
                $constructor = $reflection->getConstructor();
                if ($constructor && $constructor->getNumberOfParameters() === 0) 
                {
                    self::$instances[$calledClass]->__construct();
                }
            }
        }
        return self::$instances[$calledClass];
    }
}