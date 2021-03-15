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
        $subject =  $this->createSubject();

        /* @var $container MockObject&ContainerInterface */
        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects(static::exactly(3))
            ->method('get')
            ->withConsecutive([$keys[0]], [$keys[1]], [$keys[2]])
            ->willReturnOnConsecutiveCalls(...$values);
        $method = AccessibleMethod::create($subject, 'resolveKeys');

        $result = $method($container, $keys);

        static::assertEquals($values, $result);
    }
}
