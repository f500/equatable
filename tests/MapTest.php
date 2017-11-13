<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests;

use F500\Equatable\Map;
use F500\Equatable\Tests\Objects\EquatableObject;
use F500\Equatable\Tests\Objects\EquatableObjectWithToString;
use F500\Equatable\Vector;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class MapTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_created_empty()
    {
        $map = new Map([]);

        $this->assertInstanceOf(Map::class, $map);
    }

    /**
     * @test
     */
    public function it_is_created_with_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertInstanceOf(Map::class, $map);
    }

    /**
     * @test
     */
    public function it_is_created_with_scalar_items()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $this->assertInstanceOf(Map::class, $map);
    }

    /**
     * @test
     */
    public function it_is_created_with_object_items()
    {
        $map = new Map(['foo' => new stdClass()]);

        $this->assertInstanceOf(Map::class, $map);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_cannot_be_created_with_items_that_are_not_scalar_or_object()
    {
        $fp = fopen('php://stdout', 'w');
        fclose($fp);

        new Map(['foo' => $fp]);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_cannot_be_created_with_items_that_have_keys_that_are_not_strings()
    {
        $itemFoo = new EquatableObject('foo');

        new Map([0 => $itemFoo]);
    }

    /**
     * @test
     */
    public function it_traverses_all_equatable_items_when_iterated_over()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $iteration = 0;
        $keys      = ['foo', 'bar', 'baz'];
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        foreach ($map as $key => $value) {
            $this->assertSame($keys[$iteration], $key);
            $this->assertSame($items[$iteration], $value);

            $iteration++;
        }

        $this->assertSame(3, $iteration);
    }

    /**
     * @test
     */
    public function it_traverses_all_scalar_items_when_iterated_over()
    {
        $iteration = 0;
        $keys      = ['foo', 'bar', 'baz'];
        $items     = $keys;

        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        foreach ($map as $key => $value) {
            $this->assertSame($keys[$iteration], $key);
            $this->assertSame($items[$iteration], $value);

            $iteration++;
        }

        $this->assertSame(3, $iteration);
    }

    /**
     * @test
     */
    public function it_has_nothing_to_traverse_when_empty()
    {
        $iteration = 0;

        $map = new Map([]);

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($map as $item) {
            $iteration++;
        }

        $this->assertSame(0, $iteration);
    }

    /**
     * @test
     */
    public function it_counts_all_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertSame(3, $map->count());
        $this->assertCount(3, $map);
    }

    /**
     * @test
     */
    public function it_counts_all_scalar_items()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $this->assertSame(3, $map->count());
        $this->assertCount(3, $map);
    }

    /**
     * @test
     */
    public function it_clones_all_equatable_items_when_cloned_itself()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $items = ['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz];
        $map   = new Map($items);

        $clonedMap = clone $map;

        $this->assertCount(3, $clonedMap);

        foreach ($clonedMap as $key => $value) {
            $this->assertNotSame($items[$key], $value);
        }
    }

    /**
     * @test
     */
    public function it_exposes_its_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_its_scalar_items()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $this->assertSame('foo', $map->get('foo'));
        $this->assertSame('bar', $map->get('bar'));
        $this->assertSame('baz', $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_expose_an_item_when_the_key_does_not_exist()
    {
        $map = new Map([]);

        $map->get('foo');
    }

    /**
     * @test
     */
    public function it_searches_for_an_equatable_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);

        $this->assertSame('foo', $map->search($itemFoo2));
        $this->assertSame('bar', $map->search($itemBar2));
        $this->assertSame('baz', $map->search($itemBaz2));
    }

    /**
     * @test
     */
    public function it_searches_for_a_scalar_item()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $this->assertSame('foo', $map->search('foo'));
        $this->assertSame('bar', $map->search('bar'));
        $this->assertSame('baz', $map->search('baz'));
    }

    /**
     * @test
     */
    public function it_searches_for_the_first_occurrence_of_an_equatable_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');
        $itemBar3 = new EquatableObject('bar');

        $map = new Map(['foo1' => $itemFoo1, 'bar1' => $itemBar1, 'foo2' => $itemFoo2, 'bar2' => $itemBar2]);

        $this->assertSame('foo1', $map->search($itemFoo3));
        $this->assertSame('bar1', $map->search($itemBar3));
    }

    /**
     * @test
     */
    public function it_searches_for_the_first_occurrence_of_a_scalar_item()
    {
        $map = new Map(['foo1' => 'foo', 'bar1' => 'bar', 'foo2' => 'foo', 'bar2' => 'bar']);

        $this->assertSame('foo1', $map->search('foo'));
        $this->assertSame('bar1', $map->search('bar'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_find_an_item_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $map = new Map([]);

        $map->search($item);
    }

    /**
     * @test
     */
    public function it_searches_for_all_occurrences_of_an_equatable_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(
            ['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]
        );

        $newVector = $map->searchAll($itemFoo3);

        $expectedVector = new Vector(['foo1', 'foo2']);
        $this->assertTrue($expectedVector->equals($newVector));
    }

    /**
     * @test
     */
    public function it_searches_for_all_occurrences_of_a_scalar_item()
    {
        $map = new Map(
            ['foo1' => 'foo', 'bar' => 'bar', 'foo2' => 'foo', 'baz' => 'baz']
        );

        $newVector = $map->searchAll('foo');

        $expectedVector = new Vector(['foo1', 'foo2']);
        $this->assertTrue($expectedVector->equals($newVector));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_find_any_items_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $map = new Map([]);

        $map->searchAll($item);
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_equatable_item_or_not()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $map = new Map(['foo' => $itemFoo]);

        $this->assertTrue($map->contains($itemFoo));
        $this->assertFalse($map->contains($itemBar));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_a_scalar_item_or_not()
    {
        $map = new Map(['foo' => 'foo']);

        $this->assertTrue($map->contains('foo'));
        $this->assertFalse($map->contains('bar'));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_at_certain_key_or_not()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new Map(['foo' => $itemFoo]);

        $this->assertTrue($map->containsKey('foo'));
        $this->assertFalse($map->containsKey('bar'));
    }

    /**
     * @test
     */
    public function it_counts_a_specific_equatable_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new Map(
            [
                'foo'  => $itemFoo1,
                'bar1' => $itemBar1,
                'bar2' => $itemBar1,
                'baz1' => $itemBaz1,
                'baz2' => $itemBaz1,
                'baz3' => $itemBaz1,
            ]
        );

        $this->assertSame(1, $map->countItem($itemFoo2));
        $this->assertSame(2, $map->countItem($itemBar2));
        $this->assertSame(3, $map->countItem($itemBaz2));
    }

    /**
     * @test
     */
    public function it_counts_a_specific_scalar_item()
    {
        $map = new Map(
            [
                'foo'  => 'foo',
                'bar1' => 'bar',
                'bar2' => 'bar',
                'baz1' => 'baz',
                'baz2' => 'baz',
                'baz3' => 'baz',
            ]
        );

        $this->assertSame(1, $map->countItem('foo'));
        $this->assertSame(2, $map->countItem('bar'));
        $this->assertSame(3, $map->countItem('baz'));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_are_empty()
    {
        $map   = new Map([]);
        $other = new Map([]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_equatable_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map   = new Map(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['foo' => $itemFoo2, 'bar' => $itemBar2, 'baz' => $itemBaz2]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_scalar_items()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_equatable_items_in_a_different_order()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map   = new Map(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['bar' => $itemBar2, 'baz' => $itemBaz2, 'foo' => $itemFoo2]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_scalar_items_in_a_different_order()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['bar' => 'bar', 'baz' => 'baz', 'foo' => 'foo']);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_is_not_an_equatable_map()
    {
        $map   = new Map([]);
        $other = new stdClass();

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_a_different_amount_of_equatable_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz = new EquatableObject('baz');

        $map   = new Map(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz]);
        $other = new Map(['foo' => $itemFoo2, 'bar' => $itemBar2]);

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_a_different_amount_of_scalar_items()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['foo' => 'foo', 'bar' => 'bar']);

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_different_equatable_items()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['foo' => 'foo', 'bar' => 'bar', 'qux' => 'qux']);

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_an_equatable_item_added()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar]);

        $newMap = $map->add('baz', $itemBaz);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_a_scalar_item_added()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar']);

        $newMap = $map->add('baz', 'baz');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame('foo', $newMap->get('foo'));
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_adding_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar]);

        $map->add('baz', $itemBaz);

        $this->assertCount(2, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InRangeException
     */
    public function it_cannot_add_an_item_when_the_key_already_exists()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar]);

        $map->add('bar', $itemBaz);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_the_first_occurrence_of_an_equatable_item_removed()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $newMap = $map->remove($itemFoo3);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemFoo2, $newMap->get('foo2'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_the_first_occurrence_of_a_scalar_item_removed()
    {
        $map = new Map(['foo1' => 'foo', 'bar' => 'bar', 'foo2' => 'foo', 'baz' => 'baz']);

        $newMap = $map->remove('foo');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('foo', $newMap->get('foo2'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_removing_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz1]);

        $map->remove($itemBaz2);

        $this->assertCount(3, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz1, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_remove_an_item_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new Map([]);

        $map->remove($itemFoo);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_all_occurrences_of_an_equatable_item_removed()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $newMap = $map->removeAll($itemFoo3);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_all_occurrences_of_a_scalar_item_removed()
    {
        $map = new Map(['foo1' => 'foo', 'bar' => 'bar', 'foo2' => 'foo', 'baz' => 'baz']);

        $newMap = $map->removeAll('foo');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(2, $newMap);
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_removing_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $map->removeAll($itemFoo3);

        $this->assertCount(4, $map);
        $this->assertSame($itemFoo1, $map->get('foo1'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemFoo2, $map->get('foo2'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_remove_items_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new Map([]);

        $map->removeAll($itemFoo);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_a_key_removed()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $newMap = $map->removeKey('baz');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));
        $this->assertSame($itemBar, $newMap->get('bar'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_removing_a_key()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $map->removeKey('foo');

        $this->assertCount(3, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_remove_a_key_it_does_not_contain()
    {
        $map = new Map([]);

        $map->removeKey('foo');
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_the_first_occurrence_of_an_equatable_item_replaced()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $newMap = $map->replace($itemFoo3, $itemQux);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(4, $newMap);
        $this->assertSame($itemQux, $newMap->get('foo1'));
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemFoo2, $newMap->get('foo2'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_the_first_occurrence_of_a_scalar_item_replaced()
    {
        $map = new Map(['foo1' => 'foo', 'bar' => 'bar', 'foo2' => 'foo', 'baz' => 'baz']);

        $newMap = $map->replace('foo', 'qux');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(4, $newMap);
        $this->assertSame('qux', $newMap->get('foo1'));
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('foo', $newMap->get('foo2'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $map->replace($itemFoo3, $itemQux);

        $this->assertCount(4, $map);
        $this->assertSame($itemFoo1, $map->get('foo1'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemFoo2, $map->get('foo2'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_replace_an_item_that_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $map = new Map([]);

        $map->replace($itemFoo, $itemBar);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_all_occurrences_of_an_equatable_item_replaced()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $newMap = $map->replaceAll($itemFoo3, $itemQux);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(4, $newMap);
        $this->assertSame($itemQux, $newMap->get('foo1'));
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemQux, $newMap->get('foo2'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_all_occurrences_of_a_scalar_item_replaced()
    {
        $map = new Map(['foo1' => 'foo', 'bar' => 'bar', 'foo2' => 'foo', 'baz' => 'baz']);

        $newMap = $map->replaceAll('foo', 'qux');

        $this->assertNotSame($map, $newMap);

        $this->assertCount(4, $newMap);
        $this->assertSame('qux', $newMap->get('foo1'));
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('qux', $newMap->get('foo2'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo1' => $itemFoo1, 'bar' => $itemBar, 'foo2' => $itemFoo2, 'baz' => $itemBaz]);

        $map->replaceAll($itemFoo3, $itemQux);

        $this->assertCount(4, $map);
        $this->assertSame($itemFoo1, $map->get('foo1'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemFoo2, $map->get('foo2'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_replace_items_that_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $map = new Map([]);

        $map->replaceAll($itemFoo, $itemBar);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_a_key_replaced()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $newMap = $map->replaceKey('foo', $itemQux);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame($itemQux, $newMap->get('foo'));
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_a_key()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $map->replaceKey('foo', $itemQux);

        $this->assertCount(3, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_replace_a_key_that_it_does_not_contain()
    {
        $itemBar = new EquatableObject('bar');

        $map = new Map([]);

        $map->replaceKey('foo', $itemBar);
    }

    /**
     * @test
     */
    public function it_exposes_all_equatable_items_in_this_map_that_are_also_present_in_the_other_map()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $map   = new Map(['foo' => $itemFoo, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['bar' => $itemBar2, 'baz' => $itemBaz2, 'qux' => $itemQux]);

        $newMap = $map->intersect($other);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar1, $newMap->get('bar'));
        $this->assertSame($itemBaz1, $newMap->get('baz'));

        $newMap = $other->intersect($map);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar2, $newMap->get('bar'));
        $this->assertSame($itemBaz2, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_all_scalar_items_in_this_map_that_are_also_present_in_the_other_map()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['bar' => 'bar', 'baz' => 'baz', 'qux' => 'qux']);

        $newMap = $map->intersect($other);

        $this->assertCount(2, $newMap);
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('baz', $newMap->get('baz'));

        $newMap = $other->intersect($map);

        $this->assertCount(2, $newMap);
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_all_items_in_this_map_whose_keys_are_also_present_in_the_other_map()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $map   = new Map(['foo' => $itemFoo, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['bar' => $itemBar2, 'baz' => $itemBaz2, 'qux' => $itemQux]);

        $newMap = $map->intersectKeys($other);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar1, $newMap->get('bar'));
        $this->assertSame($itemBaz1, $newMap->get('baz'));

        $newMap = $other->intersectKeys($map);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar2, $newMap->get('bar'));
        $this->assertSame($itemBaz2, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_all_equatable_items_in_this_map_that_are_not_present_in_the_other_map()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $map   = new Map(['foo' => $itemFoo, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['bar' => $itemBar2, 'baz' => $itemBaz2, 'qux' => $itemQux]);

        $newMap = $map->diff($other);

        $this->assertCount(1, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));

        $newMap = $other->diff($map);

        $this->assertCount(1, $newMap);
        $this->assertSame($itemQux, $newMap->get('qux'));
    }

    /**
     * @test
     */
    public function it_exposes_all_scalar_items_in_this_map_that_are_not_present_in_the_other_map()
    {
        $map   = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);
        $other = new Map(['bar' => 'bar', 'baz' => 'baz', 'qux' => 'qux']);

        $newMap = $map->diff($other);

        $this->assertCount(1, $newMap);
        $this->assertSame('foo', $newMap->get('foo'));

        $newMap = $other->diff($map);

        $this->assertCount(1, $newMap);
        $this->assertSame('qux', $newMap->get('qux'));
    }

    /**
     * @test
     */
    public function it_exposes_all_items_in_this_map_whose_keys_are_not_present_in_the_other_map()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $map   = new Map(['foo' => $itemFoo, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new Map(['bar' => $itemBar2, 'baz' => $itemBaz2, 'qux' => $itemQux]);

        $newMap = $map->diffKeys($other);

        $this->assertCount(1, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));

        $newMap = $other->diffKeys($map);

        $this->assertCount(1, $newMap);
        $this->assertSame($itemQux, $newMap->get('qux'));
    }

    /**
     * @test
     */
    public function it_filters_equatable_items()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');
        $itemQux = new EquatableObjectWithToString('qux');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz, 'qux' => $itemQux]);

        $newMap = $map->filter(
            function (EquatableObjectWithToString $item): bool {
                return (substr($item->toString(), 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_filters_scalar_items()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz', 'qux' => 'qux']);

        $newMap = $map->filter(
            function (string $item): bool {
                return (substr($item, 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $newMap);
        $this->assertSame('bar', $newMap->get('bar'));
        $this->assertSame('baz', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_filters_items_by_key()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');
        $itemQux = new EquatableObjectWithToString('qux');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz, 'qux' => $itemQux]);

        $newMap = $map->filter(
            function (EquatableObjectWithToString $item, string $key): bool {
                return (substr($key, 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $newMap);
        $this->assertSame($itemBar, $newMap->get('bar'));
        $this->assertSame($itemBaz, $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_maps_equatable_items()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');

        $map = new Map(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $newMap = $map->map(
            function (EquatableObjectWithToString $item): EquatableObjectWithToString {
                return new EquatableObjectWithToString(
                    str_rot13($item->toString())
                );
            }
        );

        $this->assertCount(3, $newMap);
        $this->assertSame('sbb', $newMap->get('foo')->toString());
        $this->assertSame('one', $newMap->get('bar')->toString());
        $this->assertSame('onm', $newMap->get('baz')->toString());
    }

    /**
     * @test
     */
    public function it_maps_scalar_items()
    {
        $map = new Map(['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz']);

        $newMap = $map->map(
            function (string $item): string {
                return str_rot13($item);
            }
        );

        $this->assertCount(3, $newMap);
        $this->assertSame('sbb', $newMap->get('foo'));
        $this->assertSame('one', $newMap->get('bar'));
        $this->assertSame('onm', $newMap->get('baz'));
    }

    /**
     * @test
     */
    public function it_reduces_equatable_items_to_a_single_value()
    {
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');
        $itemQux = new EquatableObjectWithToString('qux');

        $map = new Map(['bar' => $itemBar, 'baz' => $itemBaz, 'qux' => $itemQux]);

        $reducedValue = $map->reduce(
            function (string $carry, EquatableObjectWithToString $item): string {
                return $carry . $item->toString();
            },
            'foo'
        );

        $this->assertSame('foobarbazqux', $reducedValue);
    }

    /**
     * @test
     */
    public function it_reduces_scalar_items_to_a_single_value()
    {
        $map = new Map(['bar' => 'bar', 'baz' => 'baz', 'qux' => 'qux']);

        $reducedValue = $map->reduce(
            function (string $carry, string $item): string {
                return $carry . $item;
            },
            'foo'
        );

        $this->assertSame('foobarbazqux', $reducedValue);
    }
}
