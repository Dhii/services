<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\ServiceMap;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   ServiceList
 */
class ServiceMapTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new ServiceMap([]));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];
        $subject = new ServiceMap($deps);

        static::assertEquals($deps, $subject->getDependencies());
    }

    public function testInvoke()
    {
        $services = [
            'foo' => 'hello',
            'bar' => 'world',
        ];

        $values = array_values($services);
        $map = array_combine([
            'alpha',
            'beta',
        ], array_keys($services));

        $container = MockContainer::with($this, $services);

        $subject = new ServiceMap($map);
        $result = $subject($container);

        static::assertEquals(array_combine(array_keys($map), $values), $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeNoDeps()
    {
        $container = MockContainer::create($this);

        $subject = new ServiceMap([]);
        $result = $subject($container);

        static::assertEmpty($result);
    }
}
