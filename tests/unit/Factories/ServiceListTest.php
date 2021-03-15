<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\ServiceList;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   ServiceList
 */
class ServiceListTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new ServiceList([]));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];
        $subject = new ServiceList($deps);

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

        $subject = new ServiceList($keys);
        $result = $subject($container);

        static::assertEquals($values, $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeNoDeps()
    {
        $container = MockContainer::create($this);

        $subject = new ServiceList([]);
        $result = $subject($container);

        static::assertEmpty($result);
    }
}
