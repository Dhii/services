<?php

namespace Dhii\Services\Extensions;

use Dhii\Services\Service;
use Psr\Container\ContainerInterface;

/**
 * An extension implementation that extends an array service.
 *
 * This implementation is configured with a list of service keys. These service keys will be resolved at call-time using
 * the container, and the resolved list of service values will be merged with the original service's value.
 *
 * Note: This implementation uses {@link array_merge()} to extend the original array service. This means that positional
 * entries are not overwritten, but associative entries are.
 *
 * Example usage:
 *  ```
 *  // Factories
 *  [
 *      'menu_links' => new Value([]),
 *
 *      'home_link' => new Value('Home'),
 *      'blog_link' => new Value('Blog'),
 *      'about_link' => new Value('About Us'),
 *  ]
 *
 *  // Extensions
 *  [
 *      'menu_links' => new ArrayExtension([
 *          'home_link',
 *          'blog_link',
 *          'about_link',
 *      ]),
 *  ]
 *  ```
 *
 * @since [*next-version*]
 */
class ArrayExtension extends Service
{
    /**
     * @inheritDoc
     *
     * @since [*next-version*]
     */
    public function __invoke(ContainerInterface $c, $prev = [])
    {
        return array_merge($prev, Service::resolveKeys($c, $this->dependencies));
    }
}
