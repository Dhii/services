<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\FuncService;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   FuncService
 */
class FuncServiceTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        $subject = new FuncService([], function () {
        });

        static::assertInstanceOf(Service::class, $subject);
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];

        $subject = new FuncService($deps, function () {
        });

        static::assertEquals($deps, $subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvoke()
    {
        $services = [
            'foo' => 'hello',
            'bar' => 'world',
        ];

        $keys = array_keys($services);
        $values = array_values($services);

        $container = MockContainer::with($this, $services);

        $subject = new FuncService($keys, function ($foo, $bar) {
            return [$foo, $bar];
        });

        $result = $subject($container);
        static::assertIsCallable($result);

        $return = $result();
        static::assertEquals($values, $return);
    }
}
