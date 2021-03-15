<?php

namespace Dhii\Services\Tests\Unit;

use Dhii\Services\Factory;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use Dhii\Services\Tests\Helpers\MockFunction;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   Factory
 */
class FactoryTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        $factory = new Factory([], function () {
        });

        static::assertInstanceOf(Service::class, $factory);
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];
        $definition = function () {
        };

        $factory = new Factory($deps, $definition);

        static::assertEquals($deps, $factory->getDependencies());
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

        $runCount = 0;
        $expected = 'factory_result';
        $definition = MockFunction::create($this, $expected, $values, $runCount);

        $factory = new Factory($keys, $definition);
        $actual = $factory($container);

        static::assertTrue($runCount === 1);
        static::assertSame($expected, $actual);
    }
}
