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
    public function testInvoke()
    {
        $prev = [
            'first',
            'second',
        ];

        $services = [
            'foo' => 'third',
            'bar' => 'fourth',
            'baz' => 'fifth',
        ];

        $keys = array_keys($services);
        $values = array_values($services);

        $container = MockContainer::with($this, $services);

        $subject = new ArrayExtension($keys);
        $result = $subject($container, $prev);

        static::assertEquals(array_merge($prev, $values), $result);
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
