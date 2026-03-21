<?php

declare(strict_types=1);

namespace Dhii\Services;

use Dhii\Services\Factories\Constructor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * A simple implementation for a factory service.
 *
 * This implementation will automatically resolve any specified dependencies and pass them as arguments to the
 * definition function. The container will NOT be included in the arguments.
 *
 * Example usage:
 * ```
 * new Factory(['foo', 'bar'], function($foo, $bar) {
 *      return new SomeClass($foo, $bar);
 * });
 * ```
 *
 * @see   Constructor For a similar implementation that automatically injects dependencies into constructors.
 * @see   Extension For a similar implementation that can be used with extension services.
 *
 * @psalm-import-type ServiceRef from Service
 */
class Factory extends Service
{
    use ResolveKeysCapableTrait;

    /** @var callable */
    protected $definition;

    /**
     * @inheritDoc
     *
     * @param array<ServiceRef> $dependencies A list of dependencies.
     * @param callable $definition The factory definition.
     */
    public function __construct(array $dependencies, callable $definition)
    {
        parent::__construct($dependencies);

        $this->definition = $definition;
    }

    /**
     * @inheritDoc
     *
     * @throws ContainerExceptionInterface If problem resolving from container.
     */
    #[\Override]
    public function __invoke(ContainerInterface $c)
    {
        $deps = $this->resolveDeps($c, $this->dependencies);

        return ($this->definition)(...$deps);
    }
}
