<?php

namespace Dhii\Services\Tests\Helpers;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Container mocking helper functionality.
 *
 * @since [*next-version*]
 */
class MockContainer
{
    /**
     * Creates a mock container.
     *
     * @since [*next-version*]
     *
     * @param TestCase $tCase The test case.
     *
     * @return MockObject&ContainerInterface
     */
    public static function create(TestCase $tCase)
    {
        return $tCase->getMockBuilder(ContainerInterface::class)->getMock();
    }

    /**
     * Creates a mock container that can provide certain services.
     *
     * @since [*next-version*]
     *
     * @param TestCase $tCase    The test case instance.
     * @param array    $services The map of service keys to their corresponding values.
     *
     * @return MockObject&ContainerInterface
     */
    public static function with(TestCase $tCase, array $services)
    {
        $keys = array_keys($services);
        $container = static::create($tCase);

        $container->method('get')
            ->willReturnCallback(function (string $key) use ($services) {
                if (!array_key_exists($key, $services)) {
                    throw new ((string) (new ClassBuilder())
                        ->withExtends(Exception::class)
                        ->withImplements([NotFoundExceptionInterface::class])
                    )();
                }

                return $services[$key];
            });

        $container->method('has')
                  ->willReturnCallback(function ($key) use ($keys) {
                      return in_array($key, $keys);
                  });

        return $container;
    }

    /**
     * Creates a mock container that will fail to provide a service.
     *
     * @since [*next-version*]
     *
     * @param TestCase $tCase The test case instance.
     * @param string   $key   The key of the service that the container will throw for.
     *
     * @return MockObject&ContainerInterface
     */
    public static function without(TestCase $tCase, string $key)
    {
        $exception = new class extends Exception implements NotFoundExceptionInterface {
        };

        $container = static::create($tCase);

        $container->method('get')
                  ->with($key)
                  ->willThrowException($exception);

        $container->method('has')
                  ->with($key)
                  ->willReturn(false);

        return $container;
    }
}
