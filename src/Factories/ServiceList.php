<?php

namespace Dhii\Services\Factories;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A service that aggregates other services into a list.
 *
 * This implementation is configured with a list of service keys. Those keys will be resolved at call-time using the
 * container, and the list of resolved values will be returned as the service value.
 *
 * Example usage:
 * ```
 * [
 *      'foo' => Value(5),
 *      'bar' => Value("hello"),
 *      'baz' => Value(1.61803),
 *
 *      'list' => new ServiceList([
 *          'foo',
 *          'bar',
 *          'baz'
 *      ]),
 * ]
 *
 * $list = $c->get('list'); // [5, "hello", 1.61803]
 * ```
 *
 * The array of service keys may also be associative. The array keys will be preserved in the result.
 *
 * ```
 * [
 *      'foo' => Value(5),
 *      'bar' => Value("hello"),
 *
 *      'config' => new ServiceList([
 *          'num' => 'foo',
 *          'msg' => 'bar'
 *      ]),
 * ]
 *
 * $list = $c->get('list'); // ['num' => 5, 'msg' => "hello"]
 * ```
 *
 * @since [*next-version*]
 */
class ServiceList extends Service
{
    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        return Service::resolveKeys($c, $this->dependencies);
    }
}
