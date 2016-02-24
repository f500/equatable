<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\Tests;

use F500\Equatable\ImmutableEquatableMap;
use F500\Equatable\Tests\Objects\EquatableObject;
use PHPUnit_Framework_TestCase as TestCase;
use stdClass;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class ImmutableEquatableMapTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_created_with_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertInstanceOf(ImmutableEquatableMap::class, $map);
    }

    /**
     * @test
     */
    public function it_is_created_with_equatable_items_with_integer_keys()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap([2 => $itemFoo, 4 => $itemBar, 8 => $itemBaz]);

        $this->assertInstanceOf(ImmutableEquatableMap::class, $map);
    }

    /**
     * @test
     */
    public function it_is_created_empty()
    {
        $map = new ImmutableEquatableMap([]);

        $this->assertInstanceOf(ImmutableEquatableMap::class, $map);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_cannot_be_created_with_items_that_are_not_equatable()
    {
        $map = new ImmutableEquatableMap(['foo' => 'not an equatable item']);

        $this->assertInstanceOf(ImmutableEquatableMap::class, $map);
    }

    /**
     * @test
     */
    public function it_traverses_all_items_when_iterated_over()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $iteration = 0;
        $keys      = ['foo', 'bar', 'baz'];
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

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
    public function it_traverses_all_items_with_integer_keys_when_iterated_over()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $iteration = 0;
        $keys      = [2, 4, 8];
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $map = new ImmutableEquatableMap([2 => $itemFoo, 4 => $itemBar, 8 => $itemBaz]);

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

        $map = new ImmutableEquatableMap([]);

        foreach ($map as $item) {
            $iteration++;
        }

        $this->assertSame(0, $iteration);
    }

    /**
     * @test
     */
    public function it_counts_all_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertSame(3, $map->count());
        $this->assertCount(3, $map);
    }

    /**
     * @test
     */
    public function it_clones_all_items_when_cloned_itself()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $items = ['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz];
        $map   = new ImmutableEquatableMap($items);

        $clonedMap = clone $map;

        $this->assertCount(3, $clonedMap);

        foreach ($clonedMap as $key => $value) {
            $this->assertNotSame($items[$key], $value);
        }
    }

    /**
     * @test
     */
    public function it_exposes_its_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz]);

        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz, $map->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_its_items_with_integer_keys()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap([2 => $itemFoo, 4 => $itemBar, 8 => $itemBaz]);

        $this->assertSame($itemFoo, $map->get(2));
        $this->assertSame($itemBar, $map->get(4));
        $this->assertSame($itemBaz, $map->get(8));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_expose_an_item_when_the_key_does_not_exist()
    {
        $map = new ImmutableEquatableMap([]);

        $map->get('foo');
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_does_not_support_non_integer_or_string_keys_when_exposing_items()
    {
        $map = new ImmutableEquatableMap([]);

        $map->get(1.2);
    }

    /**
     * @test
     */
    public function it_searches_for_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);

        $this->assertSame('foo', $map->search($itemFoo2));
        $this->assertSame('bar', $map->search($itemBar2));
        $this->assertSame('baz', $map->search($itemBaz2));
    }

    /**
     * @test
     */
    public function it_searches_for_an_item_with_integer_key()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new ImmutableEquatableMap([2 => $itemFoo1, 4 => $itemBar1, 8 => $itemBaz1]);

        $this->assertSame(2, $map->search($itemFoo2));
        $this->assertSame(4, $map->search($itemBar2));
        $this->assertSame(8, $map->search($itemBaz2));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_find_an_item_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $map = new ImmutableEquatableMap([]);

        $map->search($item);
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_or_not()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo]);

        $this->assertTrue($map->contains($itemFoo));
        $this->assertFalse($map->contains($itemBar));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_at_certain_key_or_not()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo]);

        $this->assertTrue($map->containsKey('foo'));
        $this->assertFalse($map->containsKey('bar'));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_at_certain_integer_key_or_not()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new ImmutableEquatableMap([2 => $itemFoo]);

        $this->assertTrue($map->containsKey(2));
        $this->assertFalse($map->containsKey(4));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_does_not_support_non_integer_or_string_keys_when_exposing_whether_it_contains_items()
    {
        $map = new ImmutableEquatableMap([]);

        $map->containsKey(1.2);
    }

    /**
     * @test
     */
    public function it_counts_a_specific_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(
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
    public function it_equals_another_map_if_both_are_empty()
    {
        $map   = new ImmutableEquatableMap([]);
        $other = new ImmutableEquatableMap([]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map   = new ImmutableEquatableMap(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new ImmutableEquatableMap(['foo' => $itemFoo2, 'bar' => $itemBar2, 'baz' => $itemBaz2]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_map_if_both_contain_equal_items_in_a_different_order()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map   = new ImmutableEquatableMap(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz1]);
        $other = new ImmutableEquatableMap(['bar' => $itemBar2, 'baz' => $itemBaz2, 'foo' => $itemFoo2]);

        $this->assertTrue($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_is_not_an_equatable_map()
    {
        $map   = new ImmutableEquatableMap([]);
        $other = new stdClass();

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_a_different_amount_of_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz = new EquatableObject('baz');

        $map   = new ImmutableEquatableMap(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz]);
        $other = new ImmutableEquatableMap(['foo' => $itemFoo2, 'bar' => $itemBar2]);

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_different_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz = new EquatableObject('baz');
        $itemQux = new EquatableObject('qux');

        $map   = new ImmutableEquatableMap(['foo' => $itemFoo1, 'bar' => $itemBar1, 'baz' => $itemBaz]);
        $other = new ImmutableEquatableMap(['foo' => $itemFoo2, 'bar' => $itemBar2, 'qux' => $itemQux]);

        $this->assertFalse($map->equals($other));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_an_item_added()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar]);

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
    public function it_exposes_a_new_map_with_an_item_with_integer_key_added()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap([2 => $itemFoo, 4 => $itemBar]);

        $newMap = $map->add(8, $itemBaz);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(3, $newMap);
        $this->assertSame($itemFoo, $newMap->get(2));
        $this->assertSame($itemBar, $newMap->get(4));
        $this->assertSame($itemBaz, $newMap->get(8));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_adding_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar]);

        $map->add('baz', $itemBaz);

        $this->assertCount(2, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_does_not_support_non_integer_or_string_keys_when_adding_items()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new ImmutableEquatableMap([]);

        $map->add(1.2, $itemFoo);
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

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar]);

        $map->add('bar', $itemBaz);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_an_item_removed()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz1]);

        $newMap = $map->remove($itemBaz2);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));
        $this->assertSame($itemBar, $newMap->get('bar'));
    }

    /**
     * @test
     */
    public function it_does_not_reorder_items_when_removing_one()
    {
        $itemFoo = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap([2 => $itemFoo, 4 => $itemBar1, 8 => $itemBaz]);

        $newMap = $map->remove($itemBar2);

        $this->assertSame($itemFoo, $newMap->get(2));
        $this->assertSame($itemBaz, $newMap->get(8));
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

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar, 'baz' => $itemBaz1]);

        $map->remove($itemBaz2);

        $this->assertCount(3, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
        $this->assertSame($itemBaz1, $map->get('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_map_with_an_item_replaced()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar]);

        $newMap = $map->replace('bar', $itemBaz);

        $this->assertNotSame($map, $newMap);

        $this->assertCount(2, $newMap);
        $this->assertSame($itemFoo, $newMap->get('foo'));
        $this->assertSame($itemBaz, $newMap->get('bar'));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $map = new ImmutableEquatableMap(['foo' => $itemFoo, 'bar' => $itemBar]);

        $map->replace('bar', $itemBaz);

        $this->assertCount(2, $map);
        $this->assertSame($itemFoo, $map->get('foo'));
        $this->assertSame($itemBar, $map->get('bar'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_does_not_support_non_integer_or_string_keys_when_replacing_items()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new ImmutableEquatableMap([]);

        $map->replace(1.2, $itemFoo);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_replace_an_item_when_the_key_does_not_exist()
    {
        $itemFoo = new EquatableObject('foo');

        $map = new ImmutableEquatableMap([]);

        $map->replace('foo', $itemFoo);
    }
}
