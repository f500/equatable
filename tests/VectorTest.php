<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests;

use F500\Equatable\Tests\Objects\EquatableObject;
use F500\Equatable\Tests\Objects\EquatableObjectWithToString;
use F500\Equatable\Vector;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class VectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_created_empty()
    {
        $vector = new Vector();

        $this->assertInstanceOf(Vector::class, $vector);
    }

    /**
     * @test
     */
    public function it_is_created_with_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertInstanceOf(Vector::class, $vector);
    }

    /**
     * @test
     */
    public function it_is_created_with_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);

        $this->assertInstanceOf(Vector::class, $vector);
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\InvalidArgumentException
     */
    public function it_cannot_be_created_with_items_that_are_not_scalar_or_object()
    {
        $fp = fopen('php://stdout', 'w');
        fclose($fp);

        new Vector(['foo' => $fp]);
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
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $vector = new Vector($items);

        foreach ($vector as $index => $value) {
            $this->assertSame($iteration, $index);
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
        $items     = ['foo', 'bar', 'baz'];

        $vector = new Vector($items);

        foreach ($vector as $index => $value) {
            $this->assertSame($iteration, $index);
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

        $vector = new Vector();

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($vector as $item) {
            $iteration++;
        }

        $this->assertSame(0, $iteration);
    }

    /**
     * @test
     */
    public function it_disregards_indexes_of_the_array_it_is_created_with()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $iteration = 0;
        $items     = [$itemFoo, $itemBar, $itemBaz];

        $vector = new Vector([2 => $itemFoo, 4 => $itemBar, 8 => $itemBaz]);

        foreach ($vector as $index => $value) {
            $this->assertSame($iteration, $index);
            $this->assertSame($items[$iteration], $value);

            $iteration++;
        }

        $this->assertSame(3, $iteration);
    }

    /**
     * @test
     */
    public function it_exposes_its_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemBaz, $vector->get(2));
    }

    /**
     * @test
     */
    public function it_exposes_its_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);

        $this->assertSame('foo', $vector->get(0));
        $this->assertSame('bar', $vector->get(1));
        $this->assertSame('baz', $vector->get(2));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_expose_an_item_when_the_index_does_not_exist()
    {
        $vector = new Vector();

        $vector->get(0);
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
        $vector = new Vector($items);

        $clonedVector = clone $vector;

        $this->assertCount(3, $clonedVector);

        foreach ($clonedVector as $index => $value) {
            $this->assertNotSame($items[$index], $value);
        }
    }

    /**
     * @test
     */
    public function it_exposes_whether_its_empty_or_not()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertFalse($vector->isEmpty());

        $vector = new Vector();

        $this->assertTrue($vector->isEmpty());
    }

    /**
     * @test
     */
    public function it_counts_all_equatable_items()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $this->assertSame(3, $vector->count());
        $this->assertCount(3, $vector);
    }

    /**
     * @test
     */
    public function it_counts_all_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);

        $this->assertSame(3, $vector->count());
        $this->assertCount(3, $vector);
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

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBar1, $itemBaz1, $itemBaz1, $itemBaz1]);

        $this->assertSame(1, $vector->countItem($itemFoo2));
        $this->assertSame(2, $vector->countItem($itemBar2));
        $this->assertSame(3, $vector->countItem($itemBaz2));
    }

    /**
     * @test
     */
    public function it_counts_a_specific_scalar_item()
    {
        $vector = new Vector(['foo', 'bar', 'bar', 'baz', 'baz', 'baz']);

        $this->assertSame(1, $vector->countItem('foo'));
        $this->assertSame(2, $vector->countItem('bar'));
        $this->assertSame(3, $vector->countItem('baz'));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_an_equatable_item_or_not()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $vector = new Vector([$itemFoo]);

        $this->assertTrue($vector->contains($itemFoo));
        $this->assertFalse($vector->contains($itemBar));
    }

    /**
     * @test
     */
    public function it_exposes_whether_it_contains_a_scalar_item_or_not()
    {
        $vector = new Vector(['foo']);

        $this->assertTrue($vector->contains('foo'));
        $this->assertFalse($vector->contains('bar'));
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

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBaz1]);

        $this->assertSame(0, $vector->search($itemFoo2));
        $this->assertSame(1, $vector->search($itemBar2));
        $this->assertSame(2, $vector->search($itemBaz2));
    }

    /**
     * @test
     */
    public function it_searches_for_a_scalar_item()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);

        $this->assertSame(0, $vector->search('foo'));
        $this->assertSame(1, $vector->search('bar'));
        $this->assertSame(2, $vector->search('baz'));
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

        $vector = new Vector([$itemFoo1, $itemBar1, $itemFoo2, $itemBar2]);

        $this->assertSame(0, $vector->search($itemFoo3));
        $this->assertSame(1, $vector->search($itemBar3));
    }

    /**
     * @test
     */
    public function it_searches_for_the_first_occurrence_of_a_scalar_item()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'bar']);

        $this->assertSame(0, $vector->search('foo'));
        $this->assertSame(1, $vector->search('bar'));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_find_an_item_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $vector = new Vector();

        $vector->search($item);
    }

    /**
     * @test
     */
    public function it_searches_for_all_occurrences_of_an_equatable_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');

        $vector    = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);
        $newVector = $vector->searchAll($itemFoo3);

        $expectedVector = new Vector([0, 2]);
        $this->assertTrue($expectedVector->equals($newVector));
    }

    /**
     * @test
     */
    public function it_searches_for_all_occurrences_of_a_scalar_item()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'baz']);

        $newVector = $vector->searchAll('foo');

        $expectedVector = new Vector([0, 2]);
        $this->assertTrue($expectedVector->equals($newVector));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_find_any_items_it_does_not_contain()
    {
        $item = new EquatableObject('foo');

        $vector = new Vector();

        $vector->searchAll($item);
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_are_empty()
    {
        $vector = new Vector();
        $other  = new Vector();

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_equatable_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBaz1]);
        $other  = new Vector([$itemFoo2, $itemBar2, $itemBaz2]);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['foo', 'bar', 'baz']);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_equatable_items_in_a_different_order()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBaz1]);
        $other  = new Vector([$itemBar2, $itemBaz2, $itemFoo2]);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_equals_another_vector_if_both_contain_equal_scalar_items_in_a_different_order()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['bar', 'baz', 'foo']);

        $this->assertTrue($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_is_not_an_equatable_vector()
    {
        $vector = new Vector();
        $other  = new stdClass();

        $this->assertFalse($vector->equals($other));
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
        $itemBaz  = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBaz]);
        $other  = new Vector([$itemFoo2, $itemBar2]);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_a_different_amount_of_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['foo', 'bar']);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_different_equatable_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo1, $itemBar1, $itemBaz]);
        $other  = new Vector([$itemFoo2, $itemBar2, $itemQux]);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_the_other_contains_different_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['foo', 'bar', 'qux']);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_each_vector_contains_the_same_equatable_items_but_in_a_different_amount()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBar2 = new EquatableObject('bar');

        $vector = new Vector([$itemFoo1, $itemFoo2, $itemFoo2]);
        $other  = new Vector([$itemBar1, $itemBar1, $itemBar2]);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_does_not_equal_if_each_vector_contains_the_same_scalar_items_but_in_a_different_amount()
    {
        $vector = new Vector(['foo', 'foo', 'foo']);
        $other  = new Vector(['bar', 'bar', 'bar']);

        $this->assertFalse($vector->equals($other));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_an_equatable_item_added()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar]);

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
    public function it_exposes_a_new_vector_with_a_scalar_item_added()
    {
        $vector = new Vector(['foo', 'bar']);

        $newVector = $vector->add('baz');

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(3, $newVector);
        $this->assertSame('foo', $newVector->get(0));
        $this->assertSame('bar', $newVector->get(1));
        $this->assertSame('baz', $newVector->get(2));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_adding_an_item()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');
        $itemBaz = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar]);

        $vector->add($itemBaz);

        $this->assertCount(2, $vector);
        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_the_first_occurrence_of_an_equatable_item_replaced()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $newVector = $vector->replace($itemFoo3, $itemQux);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(4, $newVector);
        $this->assertSame($itemQux, $newVector->get(0));
        $this->assertSame($itemBar, $newVector->get(1));
        $this->assertSame($itemFoo2, $newVector->get(2));
        $this->assertSame($itemBaz, $newVector->get(3));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_the_first_occurrence_of_a_scalar_item_replaced()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'baz']);

        $newVector = $vector->replace('foo', 'qux');

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(4, $newVector);
        $this->assertSame('qux', $newVector->get(0));
        $this->assertSame('bar', $newVector->get(1));
        $this->assertSame('foo', $newVector->get(2));
        $this->assertSame('baz', $newVector->get(3));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_an_item()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $vector->replace($itemFoo3, $itemQux);

        $this->assertCount(4, $vector);
        $this->assertSame($itemFoo1, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemFoo2, $vector->get(2));
        $this->assertSame($itemBaz, $vector->get(3));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_replace_an_item_that_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $vector = new Vector();

        $vector->replace($itemFoo, $itemBar);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_all_occurrences_of_an_equatable_item_replaced()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $newVector = $vector->replaceAll($itemFoo3, $itemQux);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(4, $newVector);
        $this->assertSame($itemQux, $newVector->get(0));
        $this->assertSame($itemBar, $newVector->get(1));
        $this->assertSame($itemQux, $newVector->get(2));
        $this->assertSame($itemBaz, $newVector->get(3));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_all_occurrences_of_a_scalar_item_replaced()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'baz']);

        $newVector = $vector->replaceAll('foo', 'qux');

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(4, $newVector);
        $this->assertSame('qux', $newVector->get(0));
        $this->assertSame('bar', $newVector->get(1));
        $this->assertSame('qux', $newVector->get(2));
        $this->assertSame('baz', $newVector->get(3));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_replacing_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $vector->replaceAll($itemFoo3, $itemQux);

        $this->assertCount(4, $vector);
        $this->assertSame($itemFoo1, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemFoo2, $vector->get(2));
        $this->assertSame($itemBaz, $vector->get(3));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_replace_items_that_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');
        $itemBar = new EquatableObject('bar');

        $vector = new Vector();

        $vector->replaceAll($itemFoo, $itemBar);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_the_first_occurrence_of_an_equatable_item_removed()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

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
    public function it_exposes_a_new_vector_with_the_first_occurrence_of_a_scalar_item_removed()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'baz']);

        $newVector = $vector->remove('foo');

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(3, $newVector);
        $this->assertSame('bar', $newVector->get(0));
        $this->assertSame('foo', $newVector->get(1));
        $this->assertSame('baz', $newVector->get(2));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_removing_an_item()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');
        $itemBaz2 = new EquatableObject('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz1]);

        $vector->remove($itemBaz2);

        $this->assertCount(3, $vector);
        $this->assertSame($itemFoo, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemBaz1, $vector->get(2));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_remove_an_item_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');

        $vector = new Vector();

        $vector->remove($itemFoo);
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_all_occurrences_of_an_equatable_item_removed()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $newVector = $vector->removeAll($itemFoo3);

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(2, $newVector);
        $this->assertSame($itemBar, $newVector->get(0));
        $this->assertSame($itemBaz, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_a_new_vector_with_all_occurrences_of_a_scalar_item_removed()
    {
        $vector = new Vector(['foo', 'bar', 'foo', 'baz']);

        $newVector = $vector->removeAll('foo');

        $this->assertNotSame($vector, $newVector);

        $this->assertCount(2, $newVector);
        $this->assertSame('bar', $newVector->get(0));
        $this->assertSame('baz', $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_is_unchanged_after_removing_items()
    {
        $itemFoo1 = new EquatableObject('foo');
        $itemFoo2 = new EquatableObject('foo');
        $itemFoo3 = new EquatableObject('foo');
        $itemBar  = new EquatableObject('bar');
        $itemBaz  = new EquatableObject('baz');

        $vector = new Vector([$itemFoo1, $itemBar, $itemFoo2, $itemBaz]);

        $vector->removeAll($itemFoo3);

        $this->assertCount(4, $vector);
        $this->assertSame($itemFoo1, $vector->get(0));
        $this->assertSame($itemBar, $vector->get(1));
        $this->assertSame($itemFoo2, $vector->get(2));
        $this->assertSame($itemBaz, $vector->get(3));
    }

    /**
     * @test
     * @expectedException \F500\Equatable\Exceptions\OutOfRangeException
     */
    public function it_cannot_remove_items_it_does_not_contain()
    {
        $itemFoo = new EquatableObject('foo');

        $vector = new Vector();

        $vector->removeAll($itemFoo);
    }

    /**
     * @test
     */
    public function it_exposes_all_equatable_items_in_this_vector_that_are_also_present_in_the_other_vector()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');
        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo, $itemBar1, $itemBaz1]);
        $other  = new Vector([$itemBar2, $itemBaz2, $itemQux]);

        $newVector = $vector->intersect($other);

        $this->assertCount(2, $newVector);
        $this->assertSame($itemBar1, $newVector->get(0));
        $this->assertSame($itemBaz1, $newVector->get(1));

        $newVector = $other->intersect($vector);

        $this->assertCount(2, $newVector);
        $this->assertSame($itemBar2, $newVector->get(0));
        $this->assertSame($itemBaz2, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_all_scalar_items_in_this_vector_that_are_also_present_in_the_other_vector()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['bar', 'baz', 'qux']);

        $newVector = $vector->intersect($other);

        $this->assertCount(2, $newVector);
        $this->assertSame('bar', $newVector->get(0));
        $this->assertSame('baz', $newVector->get(1));

        $newVector = $other->intersect($vector);

        $this->assertCount(2, $newVector);
        $this->assertSame('bar', $newVector->get(0));
        $this->assertSame('baz', $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_exposes_all_equatable_items_in_this_vector_that_are_not_present_in_the_other_vector()
    {
        $itemFoo  = new EquatableObject('foo');
        $itemBar1 = new EquatableObject('bar');
        $itemBaz1 = new EquatableObject('baz');
        $itemBar2 = new EquatableObject('bar');
        $itemBaz2 = new EquatableObject('baz');
        $itemQux  = new EquatableObject('qux');

        $vector = new Vector([$itemFoo, $itemBar1, $itemBaz1]);
        $other  = new Vector([$itemBar2, $itemBaz2, $itemQux]);

        $newVector = $vector->diff($other);

        $this->assertCount(1, $newVector);
        $this->assertSame($itemFoo, $newVector->get(0));

        $newVector = $other->diff($vector);

        $this->assertCount(1, $newVector);
        $this->assertSame($itemQux, $newVector->get(0));
    }

    /**
     * @test
     */
    public function it_exposes_all_scalar_items_in_this_vector_that_are_not_present_in_the_other_vector()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);
        $other  = new Vector(['bar', 'baz', 'qux']);

        $newVector = $vector->diff($other);

        $this->assertCount(1, $newVector);
        $this->assertSame('foo', $newVector->get(0));

        $newVector = $other->diff($vector);

        $this->assertCount(1, $newVector);
        $this->assertSame('qux', $newVector->get(0));
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

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz, $itemQux]);

        $newVector = $vector->filter(
            function (EquatableObjectWithToString $item): bool {
                return (substr($item->toString(), 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $newVector);
        $this->assertSame($itemBar, $newVector->get(0));
        $this->assertSame($itemBaz, $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_filters_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz', 'qux']);

        $newVector = $vector->filter(
            function (string $item): bool {
                return (substr($item, 0, 2) === 'ba');
            }
        );

        $this->assertCount(2, $newVector);
        $this->assertSame('bar', $newVector->get(0));
        $this->assertSame('baz', $newVector->get(1));
    }

    /**
     * @test
     */
    public function it_maps_equatable_items()
    {
        $itemFoo = new EquatableObjectWithToString('foo');
        $itemBar = new EquatableObjectWithToString('bar');
        $itemBaz = new EquatableObjectWithToString('baz');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $newVector = $vector->map(
            function (EquatableObjectWithToString $item): EquatableObjectWithToString {
                return new EquatableObjectWithToString(
                    str_rot13($item->toString())
                );
            }
        );

        $this->assertCount(3, $newVector);
        $this->assertSame('sbb', $newVector->get(0)->toString());
        $this->assertSame('one', $newVector->get(1)->toString());
        $this->assertSame('onm', $newVector->get(2)->toString());
    }

    /**
     * @test
     */
    public function it_maps_scalar_items()
    {
        $vector = new Vector(['foo', 'bar', 'baz']);

        $newVector = $vector->map(
            function (string $item): EquatableObjectWithToString {
                return new EquatableObjectWithToString(
                    str_rot13($item)
                );
            }
        );

        $this->assertCount(3, $newVector);
        $this->assertSame('sbb', $newVector->get(0)->toString());
        $this->assertSame('one', $newVector->get(1)->toString());
        $this->assertSame('onm', $newVector->get(2)->toString());
    }

    /**
     * @test
     */
    public function it_reduces_equatable_items_to_a_single_value()
    {
        $itemFoo = new EquatableObjectWithToString('bar');
        $itemBar = new EquatableObjectWithToString('baz');
        $itemBaz = new EquatableObjectWithToString('qux');

        $vector = new Vector([$itemFoo, $itemBar, $itemBaz]);

        $reduced = $vector->reduce(
            function (string $carry, EquatableObjectWithToString $item): string {
                return $carry . $item->toString();
            },
            'foo'
        );

        $this->assertSame('foobarbazqux', $reduced);
    }

    /**
     * @test
     */
    public function it_reduces_scalar_items_to_a_single_value()
    {
        $vector = new Vector(['bar', 'baz', 'qux']);

        $reduced = $vector->reduce(
            function (string $carry, string $item): string {
                return $carry . $item;
            },
            'foo'
        );

        $this->assertSame('foobarbazqux', $reduced);
    }
}
