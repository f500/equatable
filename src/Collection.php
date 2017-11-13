<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
abstract class Collection implements Equatable, Countable, IteratorAggregate
{
    protected $items = [];

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function isEmpty(): bool
    {
        return !$this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function countItem($value): int
    {
        $count = 0;

        foreach ($this->items as $item) {
            if ($this->theseAreEqual($item, $value)) {
                $count++;
            }
        }

        return $count;
    }

    public function contains($value): bool
    {
        try {
            $this->search($value);
            return true;
        } catch (OutOfRangeException $e) {
            return false;
        }
    }

    abstract public function search($value);

    public function searchAll($value): Vector
    {
        $foundKeys = [];

        foreach ($this->items as $pointer => $item) {
            if ($this->theseAreEqual($item, $value)) {
                $foundKeys[] = $pointer;
            }
        }

        if (!$foundKeys) {
            throw OutOfRangeException::valueOutOfRange($value);
        }

        return new Vector($foundKeys);
    }

    public function equals($other): bool
    {
        if (!$other instanceof static) {
            return false;
        }

        if ($this->count() !== $other->count()) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($this->countItem($item) !== $other->countItem($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * The reducer callable is given the carry value and an item,
     * and should return the value it is reduced to.
     *
     * function ($carry, $item) {
     *     return $carry . $item;
     * }
     */
    public function reduce(callable $reducer, $initial = null)
    {
        return array_reduce($this->items, $reducer, $initial);
    }

    protected function theseAreEqual($left, $right): bool
    {
        if ($left === $right) {
            return true;
        }

        if ($left instanceof Equatable && $left->equals($right)) {
            return true;
        }

        return false;
    }

    protected function guardAgainstInvalidValue($value): void
    {
        if (!is_scalar($value) && !is_object($value)) {
            throw InvalidArgumentException::invalidValueTypeInArray('values', 'scalar or object', $value);
        }
    }
}
