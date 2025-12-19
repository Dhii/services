<?php

namespace Dhii\Services\Tests\Unit;

use Dhii\Services\ResolveKeysCapableTrait as Subject;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\AccessibleMethod;
use Dhii\Services\Tests\Helpers\ClassBuilder;
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
        $mock = $this->getMockBuilder((string) (new ClassBuilder())->withUses([Subject::class]))
            ->getMock();

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
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();

        // The service dependency
        $depValue1 = new \stdClass();
        $depService = $this->getMockBuilder(Service::class)
            ->setConstructorArgs([[$keys[1]]])
            ->getMock();
        $depService->expects(static::once())
            ->method('__invoke')
            ->with($container)
            ->willReturnCallback(function (ContainerInterface $c) use ($depValue1) {
               // Simulate dependency
               $c->get('bar');
               return $depValue1;
            });

        // The callable dependency
        $depValue2 = new \stdClass();
        $depCallable = function (ContainerInterface $c) use ($depValue2) {
            // Simulate dependency
            $c->get('baz');
            return $depValue2;
        };

        // The container mock
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $i = $this->exactly(count($services));
        $container->expects($i)
            ->method('get')
            ->willReturnCallback(function (string $key) use ($i, $keys) {
              $iCount = $i->numberOfInvocations();
              $this->assertEquals($keys[$iCount - 1], $key);
            })
            ->willReturnOnConsecutiveCalls(...$values);
        
        // The SUT
        $subject =  $this->createSubject();
        $method = AccessibleMethod::create($subject, 'resolveDeps');

        // The test
        $deps = ['foo', $depService, $depCallable];
        $result = $method($container, $deps);
        $expected = [$values[0], $depValue1, $depValue2];

        static::assertEquals($expected, $result);
    }
}
