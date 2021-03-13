<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\Constructor;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Constructor
 *
 * @since [*next-version*]
 */
class ConstructorTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new Constructor(''));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = ['foo', 'bar'];

        $subject = new Constructor('', $deps);

        static::assertEquals($deps, $subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeNoDeps()
    {
        $container = MockContainer::create($this);

        $className = '\ArrayObject';
        $subject = new Constructor($className);

        $result = $subject($container);

        static::assertInstanceOf($className, $result);
    }

    /**
     * @since [*next-version*]
     */
    public function testInvokeWithDeps()
    {
        $services = [
            'msg' => 'Hello world',
            'code' => 16,
            'previous' => new Exception(),
        ];

        $keys = array_keys($services);

        $container = MockContainer::with($this, $services);

        $className = '\Exception';
        $subject = new Constructor($className, $keys);

        $result = $subject($container);

        static::assertInstanceOf($className, $result);
        static::assertEquals($services['msg'], $result->getMessage());
        static::assertEquals($services['code'], $result->getCode());
        static::assertEquals($services['previous'], $result->getPrevious());
    }
}
