<?php

namespace Dhii\Services\Tests\Unit\Extensions;

use Dhii\Services\Extensions\ArrayExtension;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   ArrayExtension
 */
class ArrayExtensionTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new ArrayExtension([]));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar', 'baz'];
        $subject = new ArrayExtension($deps);

        static::assertEquals($deps, $subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeList()
    {
        $prev = [
            'first',
            'second',
        ];

        $deps = [
            'foo',
            'bar',
            'baz'
        ];

        $services = [
            'foo' => 'third',
            'bar' => 'fourth',
            'baz' => 'fifth'
        ];

        $container = MockContainer::with($this, $services);

        $subject = new ArrayExtension($deps);
        $result = $subject($container, $prev);

        $expected = array_merge($prev, array_combine(array_keys($deps), array_values($services)));

        static::assertEquals($expected, $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeMap()
    {
        $prev = [
            'a' => 'first',
            'b' => 'second',
        ];

        $deps = [
            'c' => 'foo',
            'd' => 'bar',
            'e' => 'baz'
        ];

        $services = [
            'foo' => 'third',
            'bar' => 'fourth',
            'baz' => 'fifth'
        ];

        $container = MockContainer::with($this, $services);

        $subject = new ArrayExtension($deps);
        $result = $subject($container, $prev);

        $expected = array_merge($prev, array_combine(array_keys($deps), array_values($services)));

        static::assertEquals($expected, $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeNoDeps()
    {
        $prev = [
            'first',
            'second',
        ];

        $container = MockContainer::create($this);

        $subject = new ArrayExtension([]);
        $result = $subject($container, $prev);

        static::assertEquals($prev, $result);
    }
}
