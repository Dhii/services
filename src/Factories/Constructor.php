<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

use Dhii\Services\Factory;
use Dhii\Services\ResolveKeysCapableTrait;
use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A constructor service factory.
 *
 * This implementation is similar to {@link Factory}, in that it resolves dependencies and invokes a function. However,
 * this implementation is instead given a class name. When the service is invoked, the constructor for that class will
 * be invoked with the resolved dependencies are arguments. The created instance will be returned as the service value.
 *
 * Example usage:
 * ```
 * new Constructor(SomeClass::class, ['foo', 'bar']);
 * ```
 *
 * @see   Factory
 */
class Constructor extends Service
{
    use ResolveKeysCapableTrait;

    /** @var string */
    protected $className;

    /**
     * @inheritDoc
     *
     * @param string $className The name of the class whose constructor to invoke.
     */
    public function __construct(string $className, array $dependencies = [])
    {
        parent::__construct($dependencies);

        $this->className = $className;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $c)
    {
        $deps = $this->resolveDeps($c, $this->dependencies);
        $className = $this->className;

        return new $className(...$deps);
    }
}
