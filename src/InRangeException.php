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
final class InRangeException extends BaseException
{
    /**
     * @param int|string $key
     *
     * @return InRangeException
     */
    public static function keyInRange($key)
    {
        return new self(sprintf('Collection already contains the key %s', $key));
    }
}
