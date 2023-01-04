<?php

declare(strict_types=1);

namespace Dhii\Services\Factories;

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
 */
class StringService extends Service
{
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

    /**
     * Retrieve a service _as a string_ with the speified name from the given container.
     *
     * @param string $serviceName Name of the service to retrieve.
     * @param ContainerInterface $c The container to retrieve the service from.
     *
     * @return string The string representation of the service.
     */
    protected function resolveString(string $serviceName, ContainerInterface $c): string
    {
        $service = $c->get($serviceName);
        $value = strval($service);

        return $value;
    }
}
