<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\StringService;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   StringService
 */
class StringServiceTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new StringService(''));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];
        $subject = new StringService('', $deps);

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

        $container = MockContainer::with($this, $services);

        $format = "{0} beautiful {1}";
        $subject = new StringService($format, $keys);

        $result = $subject($container);

        static::assertEquals("hello beautiful world", $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeAssoc()
    {
        $services = [
            'foo' => 'hello',
            'bar' => 'world',
        ];

        $container = MockContainer::with($this, $services);

        $format = "{greeting} beautiful {noun}";
        $deps = [
            'greeting' => 'foo',
            'noun' => 'bar'
        ];
        $subject = new StringService($format, $deps);

        $result = $subject($container);

        static::assertEquals("hello beautiful world", $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeNoDeps()
    {
        $container = MockContainer::create($this);

        $format = "hello world";
        $subject = new StringService($format, []);

        static::assertEquals($format, $subject($container));
    }
}
