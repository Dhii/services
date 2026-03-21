<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

use Dhii\Services\ResolveKeysCapableTrait;
use Dhii\Services\Service;
use Psr\Container\ContainerExceptionInterface;
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
 * @deprecated Use {@see ServiceMap} with {@see array_values()} instead.
 */
class ServiceList extends Service
{
    use ResolveKeysCapableTrait;

    /**
     * @inheritDoc
     *
     * @throws ContainerExceptionInterface If problem resolving from container.
     */
    #[\Override]
    public function __invoke(ContainerInterface $c)
    {
        return array_values($this->resolveDeps($c, $this->dependencies));
    }
}
