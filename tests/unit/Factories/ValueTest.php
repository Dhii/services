<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\Value;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   Value
 */
class ValueTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new Value(null));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $subject = new Value(null);

        static::assertEmpty($subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvoke()
    {
        $container = MockContainer::create($this);

        $value = 'hello world';
        $subject = new Value($value);

        $result = $subject($container);

        static::assertEquals($value, $result);
    }
}
