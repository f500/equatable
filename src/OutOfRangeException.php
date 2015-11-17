<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable;

use OutOfRangeException as BaseException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class OutOfRangeException extends BaseException
{
    /**
     * @param int|string $key
     *
     * @return OutOfRangeException
     */
    public static function keyOutOfRange($key)
    {
        return new self(sprintf('Collection does not contain the key %s', $key));
    }

    /**
     * @param Equatable $value
     *
     * @return OutOfRangeException
     */
    public static function valueOutOfRange(Equatable $value)
    {
        if (method_exists($value, 'toString')) {
            $representation = $value->toString();
        } elseif (method_exists($value, '__toString')) {
            $representation = (string) $value;
        } else {
            $representation = spl_object_hash($value);
        }

        return new self(sprintf('Collection does not contain the value %s(%s)', get_class($value), $representation));
    }
}
