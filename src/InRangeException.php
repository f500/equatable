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
final class InRangeException extends BaseException
{
    public static function keyInRange(string $key): self
    {
        return new self(sprintf('Collection already contains the key "%s"', $key));
    }
}
