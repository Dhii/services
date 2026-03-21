<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

use Dhii\Services\ResolveKeysCapableTrait;
use Dhii\Services\Service;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * A service that aggregates other services into a map.
 *
 * This implementation is configured with a map of scalars (keys) to service keys (values).
 * Those keys will be resolved at call-time using the container,
 * and the map of scalar keys to resolved values will be returned as the service value.
 *
 *
 * ```
 * [
 *      'foo' => Value(5),
 *      'bar' => Value("hello"),
 *
 *      'map' => new ServiceMap([
 *          'num' => 'foo',
 *          'msg' => 'bar'
 *      ]),
 * ]
 *
 * $map = $c->get('map'); // ['num' => 5, 'msg' => "hello"]
 * ```
 *
 */
class ServiceMap extends Service
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
        return $this->resolveDeps($c, $this->dependencies);
    }
}
