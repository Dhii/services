<?php

namespace Dhii\Services\Tests\Unit;

use Dhii\Services\ResolveKeysCapableTrait as Subject;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\AccessibleMethod;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ResolveKeysCapableTraitTest extends TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @return MockObject|Subject The new instance.
     */
    protected function createSubject(): MockObject
    {
        $mock = $this->getMockBuilder(Subject::class)
            ->getMockForTrait();

        return $mock;
    }

    public function testResolveDeps()
    {
        // Container state
        $services = [
            'foo' => 100,
            'bar' => 200,
            'baz' => 300,
        ];
        $keys = array_keys($services);
        $values = array_values($services);
        $container = $this->getMockForAbstractClass(ContainerInterface::class);

        // The service dependency
        $depValue = new \stdClass();
        $depService = $this->getMockForAbstractClass(Service::class, [[$keys[1]]]);
        $depService->expects(static::once())
            ->method('__invoke')
            ->with($container)
            ->willReturnCallback(function (ContainerInterface $c) use ($depValue, $keys) {
               // Simulate dependency
               $c->get($keys[1]);
               return $depValue;
            });

        // The container mock
        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects(static::exactly(3))
                  ->method('get')
                  ->withConsecutive([$keys[0]], [$keys[1]], [$keys[2]])
                  ->willReturnOnConsecutiveCalls(...$values);
        
        // The SUT
        $subject =  $this->createSubject();
        $method = AccessibleMethod::create($subject, 'resolveDeps');

        // The test
        $args = ['foo', $depService, 'baz'];
        $result = $method($container, $args);
        $expected = [$values[0], $depValue, $values[2]];

        static::assertEquals($expected, $result);
    }
}
