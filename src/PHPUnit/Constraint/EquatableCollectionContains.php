<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\PHPUnit\Constraint;

use F500\Equatable\Equatable;
use F500\Equatable\EquatableMap;
use F500\Equatable\EquatableVector;
use PHPUnit\Framework\Constraint\TraversableContains;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EquatableCollectionContains extends TraversableContains
{
    /**
     * @inheritdoc
     */
    protected function matches($other)
    {
        if ($other instanceof EquatableMap || $other instanceof EquatableVector) {
            return $other->contains($this->value);
        }

        if ($this->value instanceof Equatable) {
            return $this->collectionContainsAnEqualEquatableObject($other);
        }

        return parent::matches($other);
    }

    /**
     * @inheritdoc
     */
    protected function failureDescription($other)
    {
        if ($other instanceof EquatableMap) {
            return 'an equatable map ' . $this->toString();
        }

        if ($other instanceof EquatableVector) {
            return 'an equatable vector ' . $this->toString();
        }

        return parent::failureDescription($other);
    }

    /**
     * @param \Traversable|array $other
     *
     * @return bool
     */
    private function collectionContainsAnEqualEquatableObject($other)
    {
        foreach ($other as $element) {
            if ($this->value->equals($element)) {
                return true;
            }
        }

        return false;
    }
}
