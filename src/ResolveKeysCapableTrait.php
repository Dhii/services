<?php

declare(strict_types=1);

namespace Dhii\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Functionality for resolving a service key.
 *
 * @psalm-import-type ServiceRef from Service
 */
trait ResolveKeysCapableTrait
{
    /**
     * Resolves a set of service keys using a given container.
     *
     * @param ContainerInterface      $c    The container to use for service resolution.
     * @param array<string|callable>  $keys The services keys to resolve.
     * @psalm-param array<ServiceRef> $keys
     *
     * @return array<array-key, mixed> A list containing the resolved service values, same order and keys as in $keys.
     *
     * @throws ContainerExceptionInterface If problem resolving from container.
     */
    protected function resolveKeys(ContainerInterface $c, array $keys): array
    {
        return $this->resolveDeps($c, $keys);
    }

    /**
     * Resolves a set of dependencies using a given container.
     *
     * @param ContainerInterface     $c    The container to use for service resolution.
     * @param array<string|callable> $deps The list of dependencies, where each is either a callable definitions or key.
     * @psalm-param ServiceRef[]     $deps
     *
     * @return array<array-key, mixed> A list containing the resolved service values, same order and keys as in $keys.
     *  If a dep is scalar and the key isn't a srin
     *
     * @throws ContainerExceptionInterface If problem resolving from container.
     */
    protected function resolveDeps(ContainerInterface $c, array $deps): array
    {
        $result = [];
        foreach ($deps as $key => $dep) {
            /** @psalm-suppress MixedAssignment We can't know the type that will be resolved */
            $result[is_scalar($dep) && !is_string($key) ? strval($dep) : $key] = $this->resolveSingleDep($c, $dep);
        }

        return $result;
    }

    /**
     * Resolves a single dependency using a given container.
     *
     * @param ContainerInterface  $c   The container to use for service resolution.
     * @param string|callable     $dep The service definition, or its key.
     * @psalm-param ServiceRef    $dep
     *
     * @return mixed The resolved service value.
     *
     * @throws ContainerExceptionInterface If problem resolving from container.
     */
    protected function resolveSingleDep(ContainerInterface $c, string|callable $dep): mixed
    {
        return is_callable($dep)
            ? $dep($c)
            : $c->get($dep);
    }
}
