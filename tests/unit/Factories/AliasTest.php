<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\Alias;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use Dhii\Services\Tests\Helpers\MockFunction;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @since [*next-version*]
 * @see   Alias
 */
class AliasTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new Alias(''));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $subject = new Alias('');

        static::assertEmpty($subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeServiceExists()
    {
        $originalKey = 'original';
        $originalVal = 'hello world';

        $container = MockContainer::with($this, [$originalKey => $originalVal]);
        $alias = new Alias($originalKey);
        $result = $alias($container);

        static::assertSame($originalVal, $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeServiceNotExists()
    {
        $originalKey = 'original';

        $container = MockContainer::without($this, $originalKey);

        $alias = new Alias($originalKey);

        $this->expectException(NotFoundExceptionInterface::class);

        $alias($container);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeDefaultDefinition()
    {
        $originalKey = 'original';

        $container = MockContainer::without($this, $originalKey);

        $default = 'default';
        $runCount = 0;
        $definition = MockFunction::create($this, $default, [$container], $runCount);

        $alias = new Alias($originalKey, $definition);
        $result = $alias($container);

        static::assertEquals($default, $result);
        static::assertEquals(1, $runCount);
    }
}
