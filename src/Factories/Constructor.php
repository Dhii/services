<?php

namespace Dhii\Services\Factories;

use Dhii\Services\Factory;
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
 * @since [*next-version*]
 * @see   Factory
 */
class Constructor extends Service
{
    /**
     * @since [*next-version*]
     *
     * @var string
     */
    protected $className;

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
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
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        $deps = Service::resolveKeys($c, $this->dependencies);

        return new $this->className(...$deps);
    }
}
