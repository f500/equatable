<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests;

use F500\Equatable\EquatableVector;
use F500\Equatable\Tests\Objects\EquatableObject;
use F500\Equatable\Tests\Objects\EquatableObjectWithToString;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EquatableVectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_created_with_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertInstanceOf(EquatableVector::class, $vector);
    }

    /**
     * @test
     */
    public function it_is_created_empty()
    {
        $vector = new EquatableVector([]);

        $this->assertInstanceOf(EquatableVector::class, $vector);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\InvalidArgumentException
     */
    public function it_cannot_be_created_with_items_that_are_not_equatable()
    {
        $vector = new EquatableVector(['not an equatable item']);

        $this->assertInstanceOf(EquatableVector::class, $vector);
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
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $vector = new EquatableVector($items);

        foreach ($vector as $key => $value) {
            $this->assertSame($iteration, $key);
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

        $vector = new EquatableVector([]);

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($vector as $item) {
            $iteration++;
        }

        $this->assertSame(0, $iteration);
    }

    /**
     * @test
     */
    public function it_disregards_keys_of_the_array_it_is_created_with()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $iteration = 0;
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $vector = new EquatableVector([2 => $itemFoo, 4 => $itemBar, 8 => $itemBaz]);

        foreach ($vector as $key => $value) {
            $this->assertSame($iteration, $key);
            $this->assertSame($items[$iteration], $value);

            $iteration++;
        }

        $this->assertSame(3, $iteration);
    }

    /**
     * @test
     */
    public function it_counts_all_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertSame(3, $vector->count());
        $this->assertCount(3, $vector);
    }

    /**
     * @test
     */
    public function it_clones_all_items_when_cloned_itself()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $items  = [$itemFoo, $itemBar, $itemBaz];
        $vector = new EquatableVector($items);

        $clonedVector = clone $vector;

        $this->assertCount(3, $clonedVector);

        foreach ($clonedVector as $key => $value) {
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

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemBaz, $vector->get(2));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_expose_an_item_when_the_key_does_not_exist()
    {
        $vector = new EquatableVector([]);

        $vector->get(0);
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

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBaz1]);

        $this->assertSame(0, $vector->search($itemFoo2));
        $this->assertSame(1, $vector->search($itemBar2));
        $this->assertSame(2, $vector->search($itemBaz2));
    }

    /**
     * @test
     */
    public function it_searches_for_the_first_occurrence_of_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $this->assertSame(0, $vector->search($itemFoo3));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_find_an_item_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $vector = new EquatableVector([]);

        $vector->search($item);
    }

    /**
     * @test
     */
    public function it_searches_for_all_occurrences_of_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $this->assertSame([0, 2], $vector->searchAll($itemFoo3));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_find_any_items_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $vector = new EquatableVector([]);

        $vector->searchAll($item);
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_or_not()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $vector = new EquatableVector([$itemFoo]);

        $this->assertTrue($vector->contains($itemFoo));
        $this->assertFalse($vector->contains($itemBar));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_item_at_certain_key_or_not()
    {
        $itemFoo = new EquatableObject('foo');

        $vector = new EquatableVector([$itemFoo]);

        $this->assertTrue($vector->containsIndex(0));
        $this->assertFalse($vector->containsIndex(1));
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

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBar1, $itemBaz1, $itemBaz1, $itemBaz1]);

        $this->assertSame(1, $vector->countItem($itemFoo2));
        $this->assertSame(2, $vector->countItem($itemBar2));
        $this->assertSame(3, $vector->countItem($itemBaz2));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_are_empty()
    {
        $vector = new EquatableVector([]);
        $other  = new EquatableVector([]);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBaz1]);
        $other  = new EquatableVector([$itemFoo2, $itemBar2, $itemBaz2]);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_items_in_a_different_order()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBaz1]);
        $other  = new EquatableVector([$itemBar2, $itemBaz2, $itemFoo2]);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_is_not_an_equatable_vector()
    {
        $vector = new EquatableVector([]);
        $other  = new stdClass();

        $this->assertFalse($vector->equals($other));
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

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBaz]);
        $other  = new EquatableVector([$itemFoo2, $itemBar2]);

        $this->assertFalse($vector->equals($other));
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

        $vector = new EquatableVector([$itemFoo1, $itemBar1, $itemBaz]);
        $other  = new EquatableVector([$itemFoo2, $itemBar2, $itemQux]);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_each_vector_contains_the_same_items_but_in_a_different_amount()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $vector = new EquatableVector([$itemFoo1, $itemFoo2, $itemFoo2]);
        $other  = new EquatableVector([$itemBar1, $itemBar1, $itemBar2]);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_an_item_added()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar]);

        $newVector = $vector->add($itemBaz);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(3, $newVector);
        $this->assertSame($itemFoo, $newVector->get(0));
        $this->assertSame($itemBar, $newVector->get(1));
        $this->assertSame($itemBaz, $newVector->get(2));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_adding_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar]);

        $vector->add($itemBaz);

        $this->assertCount(2, $vector);
        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_an_item_removed()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz1]);

        $newVector = $vector->remove($itemBaz2);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(2, $newVector);
        $this->assertSame($itemFoo, $newVector->get(0));
        $this->assertSame($itemBar, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_reorders_items_when_removing_one()
    {
        $itemFoo = new EquatableObject('foo');

        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar1, $itemBaz]);

        $newVector = $vector->remove($itemBar2);

        $this->assertSame($itemFoo, $newVector->get(0));
        $this->assertSame($itemBaz, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_removes_the_first_occurrence_of_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');

        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $newVector = $vector->remove($itemFoo3);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(3, $newVector);
        $this->assertSame($itemBar, $newVector->get(0));
        $this->assertSame($itemFoo2, $newVector->get(1));
        $this->assertSame($itemBaz, $newVector->get(2));
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

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz1]);

        $vector->remove($itemBaz2);

        $this->assertCount(3, $vector);
        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemBaz1, $vector->get(2));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_an_item_replaced()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar]);

        $newVector = $vector->replace(1, $itemBaz);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(2, $newVector);
        $this->assertSame($itemFoo, $newVector->get(0));
        $this->assertSame($itemBaz, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar]);

        $vector->replace(1, $itemBaz);

        $this->assertCount(2, $vector);
        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\OutOfRangeException
     */
    public function it_cannot_replace_an_item_when_the_key_does_not_exist()
    {
        $itemFoo = new EquatableObject('foo');

        $vector = new EquatableVector([]);

        $vector->replace(0, $itemFoo);
    }

    /**
     * @test
     */
    public function it_exposes_all_items_in_this_vector_that_are_also_present_in_the_other_vector()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new EquatableVector([$itemFoo, $itemBar1, $itemBaz1]);
        $other  = new EquatableVector([$itemBar2, $itemBaz2, $itemQux]);

        $intersect = $vector->intersect($other);

        $this->assertCount(2, $intersect);
        $this->assertSame($itemBar1, $intersect->get(0));
        $this->assertSame($itemBaz1, $intersect->get(1));

        $intersect = $other->intersect($vector);

        $this->assertCount(2, $intersect);
        $this->assertSame($itemBar2, $intersect->get(0));
        $this->assertSame($itemBaz2, $intersect->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_all_items_in_this_vector_that_are_not_present_in_the_other_vector()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');

        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new EquatableVector([$itemFoo, $itemBar1, $itemBaz1]);
        $other  = new EquatableVector([$itemBar2, $itemBaz2, $itemQux]);

        $diff = $vector->diff($other);

        $this->assertCount(1, $diff);
        $this->assertSame($itemFoo, $diff->get(0));

        $diff = $other->diff($vector);

        $this->assertCount(1, $diff);
        $this->assertSame($itemQux, $diff->get(0));
    }

    /**
     * @test
     */
    public function it_filters_items()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');
        $itemQux = new EquatableObjectWithToString('qux');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz, $itemQux]);

        $filtered = $vector->filter(
            function (EquatableObjectWithToString $item): bool {
                return (substr($item->toString(), 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $filtered);
        $this->assertSame($itemBar, $filtered->get(0));
        $this->assertSame($itemBaz, $filtered->get(1));
    }

    /**
     * @test
     */
    public function it_maps_items()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz]);

        $mapped = $vector->map(
            function (EquatableObjectWithToString $item): EquatableObjectWithToString {
                return new EquatableObjectWithToString(
                    str_rot13($item->toString())
                );
            }
        );

        $this->assertCount(3, $mapped);
        $this->assertSame('sbb', $mapped->get(0)->toString());
        $this->assertSame('one', $mapped->get(1)->toString());
        $this->assertSame('onm', $mapped->get(2)->toString());
    }

    /**
     * @test
     */
    public function it_reduces_items_to_a_single_value()
    {
        $itemFoo = new EquatableObjectWithToString('bar');
        $itemBar = new EquatableObjectWithToString('baz');
        $itemBaz = new EquatableObjectWithToString('qux');

        $vector = new EquatableVector([$itemFoo, $itemBar, $itemBaz]);

        $reduced = $vector->reduce(
            function (string $carry, EquatableObjectWithToString $item): string {
                return $carry . $item->toString();
            },
            'foo'
        );

        $this->assertSame('foobarbazqux', $reduced);
    }
}