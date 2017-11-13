<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Exceptions;

use InvalidArgumentException as BaseException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InvalidArgumentException extends BaseException
{
    public static function invalidType(string $argument, string $expectedType, $actualValue): self
    {
        return self::create(
            'Argument $%s must be of type %s, %s given',
            $argument,
            $expectedType,
            $actualValue
        );
    }

    public static function invalidValueTypeInArray(string $argument, string $expectedType, $actualValue): self
    {
        return self::create(
            'Each value in argument $%s must be of type %s, %s given',
            $argument,
            $expectedType,
            $actualValue
        );
    }

    public static function invalidKeyTypeInArray(string $argument, string $expectedType, $actualValue): self
    {
        return self::create(
            'Each key in argument $%s must be of type %s, %s given',
            $argument,
            $expectedType,
            $actualValue
        );
    }

    private static function create(string $message, string $argument, string $expectedType, $actualValue): self
    {
        if (is_object($actualValue)) {
            $actualType = get_class($actualValue);
        } elseif (is_resource($actualValue)) {
            $actualType = get_resource_type($actualValue);
        } else {
            $actualType = gettype($actualValue);
        }

        return new self(sprintf($message, $argument, $expectedType, $actualType));
    }
}
