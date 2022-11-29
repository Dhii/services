<?php

declare(strict_types=1);

namespace Dhii\Services;

use Psr\Container\ContainerInterface;

trait ResolveKeysCapableTrait
{
    /**
     * Resolves a set of service keys using a given container.
     *
     * @param ContainerInterface    $c    The container to use for service resolution.
     * @param array<Service|string> $deps The list of services dependencies, or their keys.
     *
     * @return array<string,mixed> A map of specified service keys to their resolved values,
     *                             in the same order as in $keys.
     */
    protected function resolveDeps(ContainerInterface $c, array $deps): array
    {
        $result = [];
        foreach ($deps as $dep) {
            if ($dep instanceof Service) {
                $result[] = $dep($c);
            } else {
                $result[] = $c->get($dep);
            }
        }

        return $result;
    }
}
