<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\PHPUnit;

use F500\Equatable\PHPUnit\Constraint\EquatableCollectionContains;
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
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     */
    public static function assertContains(
        $needle,
        $haystack,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        if (is_array($haystack) || is_object($haystack) && $haystack instanceof \Traversable) {
            $constraint = self::contains($needle, $checkForObjectIdentity, $checkForNonObjectIdentity);
        } elseif (is_string($haystack)) {
            if (!is_string($needle)) {
                throw \PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
            }

            $constraint = new \PHPUnit_Framework_Constraint_StringContains($needle, $ignoreCase);
        } else {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(2, 'array, traversable or string');
        }

        \PHPUnit_Framework_Assert::assertThat($haystack, $constraint, $message);
    }

    /**
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     */
    public static function assertNotContains(
        $needle,
        $haystack,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    ) {
        if (is_array($haystack) || is_object($haystack) && $haystack instanceof \Traversable) {
            $constraint = new \PHPUnit_Framework_Constraint_Not(
                self::contains($needle, $checkForObjectIdentity, $checkForNonObjectIdentity)
            );
        } elseif (is_string($haystack)) {
            if (!is_string($needle)) {
                throw \PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
            }

            $constraint = new \PHPUnit_Framework_Constraint_Not(
                new \PHPUnit_Framework_Constraint_StringContains($needle, $ignoreCase)
            );
        } else {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(2, 'array, traversable or string');
        }

        \PHPUnit_Framework_Assert::assertThat($haystack, $constraint, $message);
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

    /**
     * @param mixed $value
     * @param bool  $checkForObjectIdentity
     * @param bool  $checkForNonObjectIdentity
     *
     * @return EquatableCollectionContains
     */
    public static function contains($value, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        return new EquatableCollectionContains($value, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }
}
