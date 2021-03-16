<?php

declare(strict_types=1);

namespace Dhii\Services\Tests\Helpers;


use ReflectionClass;

class AccessibleMethod
{
    public static function create($object, string $methodName): callable
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);

        $method->setAccessible(true);
        $invocable = function (...$args) use ($object, $method) {
            return $method->invokeArgs($object, $args);
        };

        return $invocable;
    }
}
