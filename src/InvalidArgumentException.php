<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable;

use InvalidArgumentException as BaseException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InvalidArgumentException extends BaseException
{
    /**
     * @param string $argument
     * @param string $expectedType
     * @param mixed  $actualValue
     *
     * @return InvalidArgumentException
     */
    public static function invalidType($argument, $expectedType, $actualValue)
    {
        return self::create(
            'Argument $%s must be of type %s, %s given',
            $argument,
            $expectedType,
            $actualValue
        );
    }

    /**
     * @param string $argument
     * @param string $expectedType
     * @param mixed  $actualValue
     *
     * @return InvalidArgumentException
     */
    public static function invalidTypeInArray($argument, $expectedType, $actualValue)
    {
        return self::create(
            'Each value in argument $%s must be of type %s, %s given',
            $argument,
            $expectedType,
            $actualValue
        );
    }

    /**
     * @param string $message
     * @param string $argument
     * @param string $expectedType
     * @param mixed  $actualValue
     *
     * @return InvalidArgumentException
     */
    private static function create($message, $argument, $expectedType, $actualValue)
    {
        if (is_object($actualValue)) {
            $actualType = get_class($actualValue);
        } else {
            $actualType = gettype($actualValue);
        }

        return new self(sprintf($message, $argument, $expectedType, $actualType));
    }
}
