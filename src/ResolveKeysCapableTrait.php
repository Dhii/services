<?php

declare(strict_types=1);

namespace Dhii\Services;

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
     * @deprecated Use {@see resolveDeps()} instead.
     *
     * @param ContainerInterface      $c    The container to use for service resolution.
     * @param array<string|callable>  $keys The services keys to resolve.
     * @psalm-param array<ServiceRef> $keys
     *
     * @return array<int,mixed> A list containing the resolved service values, in the same order as in $keys.
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
     * @return array<int,mixed> A list containing the resolved dependencies, in the same order as given in $keys.
     */
    protected function resolveDeps(ContainerInterface $c, array $deps): array
    {
        $result = [];
        foreach ($deps as $dep) {
            $result[] = $this->resolveSingleDep($c, $dep);
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
     */
    protected function resolveSingleDep(ContainerInterface $c, $dep)
    {
        return is_callable($dep)
            ? $dep($c)
            : $c->get($dep);
    }
}
