<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable;

use Countable;
use IteratorAggregate;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface EquatableVector extends Equatable, Countable, IteratorAggregate
{
    /**
     * @param Equatable $value
     *
     * @return static
     */
    public function add(Equatable $value);

    /**
     * @param Equatable $value
     *
     * @return static
     */
    public function remove(Equatable $value);

    /**
     * @param int       $key
     * @param Equatable $value
     *
     * @return static
     */
    public function replace($key, Equatable $value);

    /**
     * @param int $key
     *
     * @return Equatable
     */
    public function get($key);

    /**
     * @param Equatable $value
     *
     * @return int
     */
    public function search(Equatable $value);

    /**
     * @param Equatable $value
     *
     * @return int[]
     */
    public function searchAll(Equatable $value);

    /**
     * @param Equatable $value
     *
     * @return bool
     */
    public function contains(Equatable $value);

    /**
     * @param int $key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * @param Equatable $value
     *
     * @return int
     */
    public function countItem(Equatable $value);
}
