<?php

namespace Dhii\Services\Factories;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * A service that references a global variable.
 *
 * Example usage:
 *
 * ```
 * global $var;
 * $var = 5;
 *
 * $service = new GlobalVarService('var')
 *
 * $service($c) // 5
 * ```
 *
 * @since [*next-version*]
 */
class GlobalVar extends Service
{
    /**
     * @since [*next-version*]
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string $name The name of the global variable.
     */
    public function __construct(string $name)
    {
        parent::__construct([]);

        $this->name = $name;
    }

    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c)
    {
        global ${$this->name};

        return ${$this->name};
    }
}
