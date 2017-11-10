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
interface EquatableVector extends Equatable, Countable, IteratorAggregate
{
    public function add(Equatable $value): self;

    public function remove(Equatable $value): self;

    public function replace(int $index, Equatable $value): self;

    public function get(int $index): Equatable;

    public function search(Equatable $value): int;

    public function searchAll(Equatable $value): array;

    public function contains(Equatable $value): bool;

    public function containsIndex(int $index): bool;

    public function countItem(Equatable $value): int;
}
