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
final class EquatableMap implements Equatable, Countable, IteratorAggregate
{
    /**
     * @var Equatable[]
     */
    private $items = [];

    /**
     * @param Equatable[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            if (!$value instanceof Equatable) {
                throw InvalidArgumentException::invalidTypeInArray('items', Equatable::class, $value);
            }

            $this->items[$key] = $value;
        }
    }

    public function __clone()
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            $items[$key] = clone $item;
        }

        $this->items = $items;
    }

    public function add(string $key, Equatable $value): EquatableMap
    {
        if ($this->containsKey($key)) {
            throw InRangeException::keyInRange($key);
        }

        $items = $this->items;

        $items[$key] = $value;

        return new static($items);
    }

    public function remove(Equatable $value): EquatableMap
    {
        $key   = $this->search($value);
        $items = $this->items;

        unset($items[$key]);

        return new static($items);
    }

    public function replace(string $key, Equatable $value): EquatableMap
    {
        if (!$this->containsKey($key)) {
            throw OutOfRangeException::keyOutOfRange($key);
        }

        $items = $this->items;

        $items[$key] = $value;

        return new static($items);
    }

    public function get(string $key): Equatable
    {
        if (!$this->containsKey($key)) {
            throw OutOfRangeException::keyOutOfRange($key);
        }

        return $this->items[$key];
    }

    public function search(Equatable $value): string
    {
        foreach ($this->items as $key => $item) {
            if ($item->equals($value)) {
                return $key;
            }
        }

        throw OutOfRangeException::valueOutOfRange($value);
    }

    public function searchAll(Equatable $value): array
    {
        $foundKeys = [];

        foreach ($this->items as $index => $item) {
            if ($item->equals($value)) {
                $foundKeys[] = $index;
            }
        }

        if (!$foundKeys) {
            throw OutOfRangeException::valueOutOfRange($value);
        }

        return $foundKeys;
    }

    public function contains(Equatable $value): bool
    {
        try {
            $this->search($value);
            return true;
        } catch (OutOfRangeException $e) {
            return false;
        }
    }

    public function containsKey(string $key): bool
    {
        return isset($this->items[$key]);
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

    public function count(): int
    {
        return count($this->items);
    }

    public function countItem(Equatable $value): int
    {
        $count = 0;

        foreach ($this->items as $item) {
            if ($item->equals($value)) {
                $count++;
            }
        }

        return $count;
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
