<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\Tests\Objects;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EquatableObjectWithToString extends EquatableObject
{
    /**
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }
}
