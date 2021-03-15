<?php

namespace Dhii\Services\Factories;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A service that always returns the same value.
 *
 * Value services will always ignore the container argument and return the same pre-configured value when invoked.
 *
 * @since [*next-version*]
 */
class Value extends Service
{
    /**
     * @since [*next-version*]
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The value.
     */
    public function __construct($value)
    {
        parent::__construct([]);

        $this->value = $value;
    }

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        return $this->value;
    }
}
