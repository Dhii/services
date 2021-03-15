<?php

namespace Dhii\Services\Tests\Helpers;

use PHPUnit\Framework\TestCase;

class MockFunction
{
    public static function create(TestCase $tCase, $return, array $expectedArgs, int &$runCount = 0)
    {
        return function (...$args) use ($tCase, $return, $expectedArgs, &$runCount) {
            $runCount++;

            foreach ($expectedArgs as $idx => $expected) {
                $tCase::assertEquals($expected, $args[$idx], "Argument #{$idx} mismatch");
            }

            return $return;
        };
    }
}
