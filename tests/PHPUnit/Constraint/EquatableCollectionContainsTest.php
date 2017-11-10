<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests\PHPUnit\Constraint;

use F500\Equatable\EquatableMap;
use F500\Equatable\EquatableVector;
use F500\Equatable\PHPUnit\Constraint\EquatableCollectionContains;
use F500\Equatable\Tests\Objects\EquatableObject;
use PHPUnit\Framework\TestCase;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EquatableCollectionContainsTest extends TestCase
{
    /**
     * @test
     */
    public function it_passes_evaluation_when_an_equatable_map_contains_the_value()
    {
        $value = new EquatableObject('foo');
        $map   = new EquatableMap(['foo' => new EquatableObject('foo')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertNull($constraint->evaluate($map));
    }

    /**
     * @test
     */
    public function it_evaluates_to_true_when_an_equatable_vector_contains_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new EquatableVector([new EquatableObject('foo')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertTrue($constraint->evaluate($vector, '', true));
    }

    /**
     * @test
     * @expectedException \PHPUnit\Framework\ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that an equatable map contains
     */
    public function it_fails_to_evaluate_when_an_equatable_map_does_not_contain_the_value()
    {
        $value = new EquatableObject('foo');
        $map   = new EquatableMap(['bar' => new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $constraint->evaluate($map);
    }

    /**
     * @test
     * @expectedException \PHPUnit\Framework\ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that an equatable vector contains
     */
    public function it_fails_to_evaluate_when_an_equatable_vector_does_not_contain_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new EquatableVector(['bar' => new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $constraint->evaluate($vector);
    }

    /**
     * @test
     */
    public function it_evaluates_to_false_when_an_equatable_vector_does_not_contain_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new EquatableVector([new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertFalse($constraint->evaluate($vector, '', true));
    }

    /**
     * @test
     */
    public function it_passes_evaluation_when_a_collection_contains_an_equatable_object()
    {
        $value = new EquatableObject('foo');
        $array = [new EquatableObject('foo')];

        $constraint = new EquatableCollectionContains($value);

        $this->assertNull($constraint->evaluate($array));
    }

    /**
     * @test
     * @expectedException \PHPUnit\Framework\ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that an array contains
     */
    public function it_fails_to_evaluate_when_a_collection_does_not_contain_an_equatable_object()
    {
        $value = new EquatableObject('foo');
        $array = [new EquatableObject('bar')];

        $constraint = new EquatableCollectionContains($value);

        $constraint->evaluate($array);
    }

    /**
     * @test
     */
    public function it_passes_evaluation_when_a_collection_contains_a_non_equatable_object()
    {
        $value = 'foo';
        $array = ['foo'];

        $constraint = new EquatableCollectionContains($value);

        $this->assertNull($constraint->evaluate($array));
    }

    /**
     * @test
     * @expectedException \PHPUnit\Framework\ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that an array contains
     */
    public function it_fails_to_evaluate_when_a_collection_does_not_contain_a_non_equatable_object()
    {
        $value = 'foo';
        $array = ['bar'];

        $constraint = new EquatableCollectionContains($value);

        $constraint->evaluate($array);
    }
}
