<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

use F500\Equatable\Exceptions\OutOfRangeException;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class Vector extends Collection
{
    public function __construct(array $values = [])
    {
        foreach ($values as $value) {
            $this->guardAgainstInvalidValue($value);
            $this->items[] = $value;
        }
    }

    public function __clone()
    {
        $items = [];

        foreach ($this->items as $item) {
            if (is_object($item)) {
                $items[] = clone $item;
            } else {
                $items[] = $item;
            }
        }

        $this->items = $items;
    }

    public function get(int $index)
    {
        if (!$this->containsIndex($index)) {
            throw OutOfRangeException::doesNotContainIndex($index);
        }

        return $this->items[$index];
    }

    public function search($value): int
    {
        foreach ($this->items as $index => $item) {
            if ($this->theseAreEqual($item, $value)) {
                return $index;
            }
        }

        throw OutOfRangeException::doesNotContainValue($value);
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

    public function add($value): self
    {
        $items = $this->items;

        $items[] = $value;

        return new self($items);
    }

    public function replace($searchValue, $replacementValue): self
    {
        $items = $this->items;
        $index = $this->search($searchValue);

        $items[$index] = $replacementValue;

        return new self($items);
    }

    public function replaceAll($searchValue, $replacementValue): self
    {
        $items   = $this->items;
        $indexes = $this->searchAll($searchValue);

        if ($indexes->isEmpty()) {
            return $this;
        }

        foreach ($indexes as $index) {
            $items[$index] = $replacementValue;
        }

        return new self($items);
    }

    public function remove($value): self
    {
        $items = $this->items;
        $index = $this->search($value);

        unset($items[$index]);

        return new self($items);
    }

    public function removeAll($value): self
    {
        $items   = $this->items;
        $indexes = $this->searchAll($value);

        if ($indexes->isEmpty()) {
            return $this;
        }

        foreach ($indexes as $index) {
            unset($items[$index]);
        }

        return new self($items);
    }

    public function merge(self $other): self
    {
        $items = array_merge($this->items, $other->items);

        return new self($items);
    }

    public function intersect(self $other): self
    {
        $items = [];

        foreach ($this->items as $item) {
            if ($other->contains($item)) {
                $items[] = $item;
            }
        }

        return new self($items);
    }

    public function diff(self $other): self
    {
        $items = [];

        foreach ($this->items as $item) {
            if (!$other->contains($item)) {
                $items[] = $item;
            }
        }

        return new self($items);
    }

    /**
     * The filter callable is given an item, and should return
     * a boolean indicating whether the item remains or not.
     *
     * function ($item): bool {
     *     return true;
     * }
     */
    public function filter(callable $filter): self
    {
        $items = array_filter($this->items, $filter);

        return new self($items);
    }

    /**
     * The mapper callable is given an item, and should return
     * a new value to use in it's place.
     *
     * function ($item) {
     *     return $item;
     * }
     */
    public function map(callable $mapper): self
    {
        $items = array_map($mapper, $this->items);

        return new self($items);
    }

    private function containsIndex(int $index): bool
    {
        return isset($this->items[$index]);
    }
}
