<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class Map extends Collection
{
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->guardAgainstNonScalarOrObject($value);
            $this->items[$key] = $value;
        }
    }

    public function __clone()
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            if (is_object($item)) {
                $items[$key] = clone $item;
            } else {
                $items[$key] = $item;
            }
        }

        $this->items = $items;
    }

    public function add(string $key, $value): self
    {
        if ($this->containsKey($key)) {
            throw InRangeException::keyInRange($key);
        }

        $items = $this->items;

        $items[$key] = $value;

        return new self($items);
    }

    public function remove($value): self
    {
        $key   = $this->search($value);
        $items = $this->items;

        unset($items[$key]);

        return new self($items);
    }

    public function replace($searchValue, $replacementValue): self
    {
        $items = $this->items;
        $key   = $this->search($searchValue);

        $items[$key] = $replacementValue;

        return new self($items);
    }

    public function get(string $key)
    {
        if (!$this->containsKey($key)) {
            throw OutOfRangeException::keyOutOfRange($key);
        }

        return $this->items[$key];
    }

    public function search($value): string
    {
        foreach ($this->items as $key => $item) {
            if ($this->theseAreEqual($item, $value)) {
                return $key;
            }
        }

        throw OutOfRangeException::valueOutOfRange($value);
    }

    public function containsKey(string $key): bool
    {
        return isset($this->items[$key]);
    }

    public function intersect(self $other): self
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            if ($other->contains($item)) {
                $items[$key] = $item;
            }
        }

        return new self($items);
    }

    public function intersectKeys(self $other): self
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            if ($other->containsKey($key)) {
                $items[$key] = $item;
            }
        }

        return new self($items);
    }

    public function diff(self $other): self
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            if (!$other->contains($item)) {
                $items[$key] = $item;
            }
        }

        return new self($items);
    }

    public function diffKeys(self $other): self
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            if (!$other->containsKey($key)) {
                $items[$key] = $item;
            }
        }

        return new self($items);
    }

    /**
     * The filter callable is given an item and it's key, and should
     * return a boolean indicating whether the item remains or not.
     *
     * function ($item, $key): bool {
     *     return true;
     * }
     */
    public function filter(callable $filter): self
    {
        $items = array_filter($this->items, $filter, ARRAY_FILTER_USE_BOTH);

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
}