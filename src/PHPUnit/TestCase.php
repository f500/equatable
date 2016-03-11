<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\PHPUnit;

use F500\Equatable\PHPUnit\Constraint\IsEqual;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    public static function assertEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        $constraint = self::equalTo($expected, $delta, $maxDepth, $canonicalize, $ignoreCase);

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * @inheritdoc
     */
    public static function assertNotEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        $constraint = new \PHPUnit_Framework_Constraint_Not(
            self::equalTo($expected, $delta, $maxDepth, $canonicalize, $ignoreCase)
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * @inheritdoc
     */
    public static function equalTo($value, $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        return new IsEqual(
            $value,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * @inheritdoc
     */
    public static function attributeEqualTo(
        $attributeName,
        $value,
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return self::attribute(
            self::equalTo($value, $delta, $maxDepth, $canonicalize, $ignoreCase),
            $attributeName
        );
    }
}
