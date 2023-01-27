<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

use Dhii\Services\ResolveKeysCapableTrait;
use Dhii\Services\Service;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

/**
 * A factory for string values. Supports interpolation with dependent service values.
 *
 * Example usage:
 *  ```
 *  [
 *      'service_a' => new FormatStr('John Smith'),
 *      'service_b' => new FormatStr('User name is: {0}', ['service_a']),
 *      'service_c' => new FormatStr('{day} {month}', [
 *          'day'   => 'date/day',
 *          'month' => 'date/month',
 *      ]),
 *  ]
 *  ```
 *
 * @psalm-import-type ServiceRef from Service
 */
class StringService extends Service
{
    use ResolveKeysCapableTrait;

    /** @var string */
    protected $format;

    /**
     * @inheritDoc
     *
     * @param string $format The format string. Substrings wrapped in curly braces will be interpolated with the
     *                       string value of the resolved dependency at the index indicated by that substring. The index
     *                       may be either numerical (for positional dependency arrays), or a string (for associative
     *                       dependency arrays).
     */
    public function __construct(string $format, array $dependencies = [])
    {
        parent::__construct($dependencies);

        $this->format = $format;
    }

    /**
     * Retrieve a service _as a string_ with the specified name from the given container.
     *
     * @param ServiceRef $serviceRef The service, or its name in the container..
     * @param ContainerInterface $c The container to retrieve the service from.
     *
     * @return string The string representation of the service.
     *
     * @throws UnexpectedValueException If service could be converted to string.
     */
    protected function resolveString($serviceRef, ContainerInterface $c): string
    {
        $service = $this->resolveSingleDep($c, $serviceRef);

        if (!is_null($service) && !is_scalar($service) && !is_object($service)) {
            throw new UnexpectedValueException(sprintf(
                'Service must be of type null|scalar|object to be stringable; %1$s received',
                gettype($service)
            ));
        }

        return strval($service);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $c)
    {
        if (empty($this->dependencies)) {
            return $this->format;
        }

        $replace = [];
        foreach ($this->dependencies as $idx => $dependency) {
            $idx = (string) $idx;
            $replace['{' . $idx . '}'] = $this->resolveString($dependency, $c);
        }

        return strtr($this->format, $replace);
    }
}
