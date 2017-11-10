<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable;

use Countable;
use IteratorAggregate;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface EquatableMap extends Equatable, Countable, IteratorAggregate
{
    public function add(string $key, Equatable $value): self;

    public function remove(Equatable $value): self;

    public function replace(string $key, Equatable $value);

    public function get(string $key): Equatable;

    public function search(Equatable $value): string;

    public function searchAll(Equatable $value): array;

    public function contains(Equatable $value): bool;

    public function containsKey(string $key): bool;

    public function countItem(Equatable $value): int;
}
