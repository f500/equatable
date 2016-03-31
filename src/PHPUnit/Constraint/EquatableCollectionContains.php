<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\PHPUnit\Constraint;

use F500\Equatable\EquatableMap;
use F500\Equatable\EquatableVector;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EquatableCollectionContains extends \PHPUnit_Framework_Constraint_TraversableContains
{
    /**
     * @inheritdoc
     */
    protected function matches($other)
    {
        if ($other instanceof EquatableMap || $other instanceof EquatableVector) {
            return $other->contains($this->value);
        }

        return parent::matches($other);
    }

    /**
     * @inheritdoc
     */
    protected function failureDescription($other)
    {
        return sprintf(
            '%s %s',
            $other instanceof EquatableMap ? 'an equatable map' : 'an equatable vector',
            $this->toString()
        );
    }
}
