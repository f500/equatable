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
trait EquatableAssertionCapabilities
{
    /**
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
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

        \PHPUnit_Framework_Assert::assertThat($actual, $constraint, $message);
    }

    /**
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
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

        \PHPUnit_Framework_Assert::assertThat($actual, $constraint, $message);
    }

    /**
     * @param mixed $value
     * @param float $delta
     * @param int   $maxDepth
     * @param bool  $canonicalize
     * @param bool  $ignoreCase
     *
     * @return IsEqual
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
     * @param string $attributeName
     * @param mixed  $value
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     *
     * @return \PHPUnit_Framework_Constraint_Attribute
     */
    public static function attributeEqualTo(
        $attributeName,
        $value,
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    ) {
        return \PHPUnit_Framework_Assert::attribute(
            self::equalTo($value, $delta, $maxDepth, $canonicalize, $ignoreCase),
            $attributeName
        );
    }
}
