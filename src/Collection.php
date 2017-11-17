<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

use ArrayIterator;
use Countable;
use F500\Equatable\Exceptions\InvalidArgumentException;
use F500\Equatable\Exceptions\OutOfRangeException;
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
        $foundPointers = [];

        foreach ($this->items as $pointer => $item) {
            if ($this->theseAreEqual($item, $value)) {
                $foundPointers[] = $pointer;
            }
        }

        return new Vector($foundPointers);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function first()
    {
        if ($this->isEmpty()) {
            throw OutOfRangeException::doesNotContainAnything();
        }

        return reset($this->items);
    }

    public function last()
    {
        if ($this->isEmpty()) {
            throw OutOfRangeException::doesNotContainAnything();
        }

        return end($this->items);
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

    protected function guardAgainstInvalidValue($value)
    {
        if (!is_scalar($value) && !is_object($value)) {
            throw InvalidArgumentException::invalidValueTypeInArray('values', 'scalar or object', $value);
        }
    }
}
