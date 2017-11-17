<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Exceptions;

use OutOfRangeException as BaseException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class OutOfRangeException extends BaseException
{
    public static function doesNotContainIndex(int $index): self
    {
        return new self(sprintf('Collection does not contain the index %d', $index));
    }

    public static function doesNotContainKey(string $key): self
    {
        return new self(sprintf('Collection does not contain the key "%s"', $key));
    }

    public static function doesNotContainValue($value): self
    {
        if (!is_object($value)) {
            return new self(
                sprintf('Collection does not contain the value %s(%s)', gettype($value), var_export($value, true))
            );
        }

        if (method_exists($value, 'toString')) {
            $representation = var_export($value->toString(), true);
        } elseif (method_exists($value, '__toString')) {
            $representation = var_export((string) $value, true);
        } else {
            $representation = spl_object_hash($value);
        }

        return new self(
            sprintf('Collection does not contain the value %s(%s)', get_class($value), $representation)
        );
    }

    public static function doesNotContainAnything(): self
    {
        return new self('Collection does not contain anything');
    }
}
