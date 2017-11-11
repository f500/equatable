<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

use OutOfRangeException as BaseException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class OutOfRangeException extends BaseException
{
    public static function indexOutOfRange(int $index): self
    {
        return new self(sprintf('Collection does not contain the index %d', $index));
    }

    public static function keyOutOfRange(string $key): self
    {
        return new self(sprintf('Collection does not contain the key "%s"', $key));
    }

    public static function valueOutOfRange($value): self
    {
        if (!is_object($value)) {
            return new self(
                sprintf('Collection does not contain the value %s(%s)', gettype($value), print_r($value, true))
            );
        }

        if (method_exists($value, 'toString')) {
            $representation = $value->toString();
        } elseif (method_exists($value, '__toString')) {
            $representation = (string) $value;
        } else {
            $representation = spl_object_hash($value);
        }

        return new self(
            sprintf('Collection does not contain the value %s(%s)', get_class($value), $representation)
        );
    }
}
