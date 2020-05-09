<?php

namespace Dhii\Services\Tests\Unit;

use Dhii\Services\Service;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @since [*next-version*]
 *
 * @see   Service
 */
class ServiceTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsCallable()
    {
        $service = $this->getMockForAbstractClass(Service::class, [[]]);

        static::assertInternalType('callable', $service);
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $deps = [
            'foo',
            'bar',
            'baz',
        ];

        /* @var $service MockObject&Service */
        $service = $this->getMockBuilder(Service::class)
                        ->setConstructorArgs([$deps])
                        ->setMethods(['__invoke'])
                        ->getMockForAbstractClass();

        static::assertEquals($deps, $service->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testResolveKeys()
    {
        $services = [
            'foo' => 100,
            'bar' => 200,
            'baz' => 300,
        ];

        $keys = array_keys($services);
        $values = array_values($services);

        /* @var $container MockObject&ContainerInterface */
        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects(static::exactly(3))
                  ->method('get')
                  ->withConsecutive([$keys[0]], [$keys[1]], [$keys[2]])
                  ->willReturnOnConsecutiveCalls(...$values);

        $result = Service::resolveKeys($container, $keys);

        static::assertEquals($values, $result);
    }
}
