<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable;

use ArrayIterator;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ImmutableEquatableVector implements EquatableVector
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

    /**
     * @inheritdoc
     */
    public function add(Equatable $value)
    {
        $items = $this->items;

        $items[] = $value;

        return new static($items);
    }

    /**
     * @inheritdoc
     */
    public function remove(Equatable $value)
    {
        $key   = $this->search($value);
        $items = $this->items;

        unset($items[$key]);

        return new static($items);
    }

    /**
     * @inheritdoc
     */
    public function replace($key, Equatable $value)
    {
        if (!$this->containsKey($key)) {
            throw OutOfRangeException::keyOutOfRange($key);
        }

        $items = $this->items;

        $items[$key] = $value;

        return new static($items);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (!$this->containsKey($key)) {
            throw OutOfRangeException::keyOutOfRange($key);
        }

        return $this->items[$key];
    }

    /**
     * @inheritdoc
     */
    public function search(Equatable $value)
    {
        foreach ($this->items as $index => $item) {
            if ($item->equals($value)) {
                return $index;
            }
        }

        throw OutOfRangeException::valueOutOfRange($value);
    }

    /**
     * @inheritdoc
     */
    public function searchAll(Equatable $value)
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

    /**
     * @inheritdoc
     */
    public function contains(Equatable $value)
    {
        try {
            $this->search($value);
            return true;
        } catch (OutOfRangeException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function containsKey($key)
    {
        if (!is_int($key)) {
            throw InvalidArgumentException::invalidType('key', 'integer', $key);
        }

        return isset($this->items[$key]);
    }

    /**
     * @inheritdoc
     */
    public function equals($other)
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
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function countItem(Equatable $value)
    {
        $count = 0;

        foreach ($this->items as $item) {
            if ($item->equals($value)) {
                $count++;
            }
        }

        return $count;
    }
}
