<?php

namespace Dhii\Services\Tests\Unit\Factories;

use Dhii\Services\Factories\GlobalVar;
use Dhii\Services\Service;
use Dhii\Services\Tests\Helpers\MockContainer;
use PHPUnit\Framework\TestCase;

/**
 * @since [*next-version*]
 * @see   GlobalVar
 */
class GlobalVarTest extends TestCase
{
    /**
     * @since [*next-version*]
     */
    public function testIsService()
    {
        static::assertInstanceOf(Service::class, new GlobalVar(''));
    }

    /**
     * @since [*next-version*]
     */
    public function testGetDependencies()
    {
        $subject = new GlobalVar('');

        static::assertEmpty($subject->getDependencies());
    }

    /**
     * @since [*next-version*]
     */
    public function testInvoke()
    {
        $name = 'someGlobal';
        $value = 'hello world';

        global $$name;
        $$name = $value;

        $subject = new GlobalVar($name);
        $result = $subject(MockContainer::create($this));

        static::assertSame($value, $result);
    }
}
