<?php

namespace Dhii\Services\Factories;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A function service.
 *
 * Services of this type will resolve to a function.
 * Example usage:
 *
 * ```
 * $service = new FuncService(['foo', 'bar'], function ($foo, $bar) {
 *      return $foo + $bar;
 * });
 *
 * $fn = $service($c);
 * $fn();
 * ```
 *
 * The function may accept additional call-time arguments. These arguments will be passed _before_ the resolved
 * dependencies:
 *
 * ```
 * $service = new FuncService(['foo', 'bar'], function ($arg1, $arg2, $foo, $bar) {
 *      return ($arg1 + $arg2) * ($foo + $bar);
 * });
 *
 * $fn = $service($c);
 * $fn($arg1, $arg2);
 * ```
 *
 * @since [*next-version*]
 */
class FuncService extends Service
{
    /**
     * @since [*next-version*]
     *
     * @var callable
     */
    protected $function;

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     *
     * @param callable $function The function to return when the service is created.
     */
    public function __construct(array $dependencies, callable $function)
    {
        parent::__construct($dependencies);
        $this->function = $function;
    }

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        $deps = Service::resolveKeys($c, $this->dependencies);

        return function (...$args) use ($deps) {
            return ($this->function)(...$args, ...$deps);
        };
    }
}
