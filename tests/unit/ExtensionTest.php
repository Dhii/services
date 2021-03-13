<?php

namespace Dhii\Services\Tests\Unit;

use Dhii\Services\Extension;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use Dhii\Services\Tests\Helpers\MockFunction;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   Extension
 */
class ExtensionTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        $extension = new Extension([], function () {
        });

        static::assertInstanceOf(Service::class, $extension);
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];
        $definition = function () {
        };

        $extension = new Extension($deps, $definition);

        static::assertEquals($deps, $extension->getDependencies());
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

        $prev = 'service_value';
        $expected = 'extended_value';

        $runCount = 0;
        $args = array_merge([$prev], $values);
        $definition = MockFunction::create($this, $expected, $args, $runCount);

        $container = MockContainer::with($this, $services);
        $extension = new Extension($keys, $definition);

        $actual = $extension($container, $prev);

        static::assertEquals(1, $runCount);
        static::assertSame($expected, $actual);
    }
}
