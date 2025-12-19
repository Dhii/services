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
        $service = $this->getMockBuilder(Service::class)
            ->setConstructorArgs([[]])
            ->onlyMethods(['__invoke'])
            ->getMock();

        $service->method('__invoke');

        $this->assertIsCallable($service);
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
                        ->onlyMethods(['__invoke'])
                        ->getMock();

        static::assertEquals($deps, $service->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testWithDependencies()
    {
        $oldDeps = ['foo', 'bar'];
        $newDeps = ['baz', 'zap'];

        /* @var $oldService MockObject&Service */
        $oldService = $this->getMockBuilder(Service::class)
                        ->setConstructorArgs([$oldDeps])
                        ->onlyMethods(['__invoke'])
                        ->getMock();

        /* @var $newService MockObject&Service */
        $newService = $oldService->withDependencies($newDeps);

        static::assertInstanceOf(Service::class, $newService);
        static::assertEquals($oldDeps, $oldService->getDependencies());
        static::assertEquals($newDeps, $newService->getDependencies());
    }
}
