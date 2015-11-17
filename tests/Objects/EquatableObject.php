<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\Tests\Objects;

use F500\Equatable\Equatable;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EquatableObject implements Equatable
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Contains the SPL Object Hash so that a == comparison will always fail.
     * This way we make sure the equals() method is used to check equality.
     *
     * @var string
     */
    protected $hash;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->hash  = spl_object_hash($this);
    }

    /**
     * @inheritdoc
     */
    public function equals($other)
    {
        return ($other instanceof static && $other->value === $this->value);
    }
}
