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
final class EquatableVector implements Equatable, Countable, IteratorAggregate
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
        foreach ($items as $item) {
            if (!$item instanceof Equatable) {
                throw InvalidArgumentException::invalidTypeInArray('items', Equatable::class, $item);
            }

            $this->items[] = $item;
        }
    }

    public function __clone()
    {
        $items = [];

        foreach ($this->items as $item) {
            $items[] = clone $item;
        }

        $this->items = $items;
    }

    public function add(Equatable $value): EquatableVector
    {
        $items = $this->items;

        $items[] = $value;

        return new static($items);
    }

    public function remove(Equatable $value): EquatableVector
    {
        $index = $this->search($value);
        $items = $this->items;

        unset($items[$index]);

        return new static($items);
    }

    public function replace(int $index, Equatable $value): EquatableVector
    {
        if (!$this->containsIndex($index)) {
            throw OutOfRangeException::indexOutOfRange($index);
        }

        $items = $this->items;

        $items[$index] = $value;

        return new static($items);
    }

    public function get(int $index): Equatable
    {
        if (!$this->containsIndex($index)) {
            throw OutOfRangeException::indexOutOfRange($index);
        }

        return $this->items[$index];
    }

    public function search(Equatable $value): int
    {
        foreach ($this->items as $index => $item) {
            if ($item->equals($value)) {
                return $index;
            }
        }

        throw OutOfRangeException::valueOutOfRange($value);
    }

    public function searchAll(Equatable $value): array
    {
        $foundIndexes = [];

        foreach ($this->items as $index => $item) {
            if ($item->equals($value)) {
                $foundIndexes[] = $index;
            }
        }

        if (!$foundIndexes) {
            throw OutOfRangeException::valueOutOfRange($value);
        }

        return $foundIndexes;
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

    public function containsIndex(int $index): bool
    {
        return isset($this->items[$index]);
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
