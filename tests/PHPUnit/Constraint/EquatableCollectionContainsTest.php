<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\Tests\PHPUnit\Constraint;

use F500\Equatable\ImmutableEquatableMap;
use F500\Equatable\ImmutableEquatableVector;
use F500\Equatable\PHPUnit\Constraint\EquatableCollectionContains;
use F500\Equatable\Tests\Objects\EquatableObject;
use PHPUnit_Framework_TestCase as TestCase;

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
        $map   = new ImmutableEquatableMap(['foo' => new EquatableObject('foo')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertNull($constraint->evaluate($map));
    }

    /**
     * @test
     */
    public function it_evaluates_to_true_when_an_equatable_vector_contains_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new ImmutableEquatableVector([new EquatableObject('foo')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertTrue($constraint->evaluate($vector, '', true));
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     */
    public function it_fails_to_evaluate_when_an_equatable_map_does_not_contain_the_value()
    {
        $value = new EquatableObject('foo');
        $map   = new ImmutableEquatableMap(['bar' => new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertFalse($constraint->evaluate($map));
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     */
    public function it_fails_to_evaluate_when_an_equatable_vector_does_not_contain_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new ImmutableEquatableVector(['bar' => new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertFalse($constraint->evaluate($vector));
    }

    /**
     * @test
     */
    public function it_evaluates_to_false_when_an_equatable_vector_does_not_contain_the_value()
    {
        $value  = new EquatableObject('foo');
        $vector = new ImmutableEquatableVector([new EquatableObject('bar')]);

        $constraint = new EquatableCollectionContains($value);

        $this->assertFalse($constraint->evaluate($vector, '', true));
    }

    /**
     * @test
     */
    public function it_falls_back_to_parent_when_not_an_equatable_collection()
    {
        $value = new EquatableObject('foo');
        $array = [new EquatableObject('bar')];

        $constraint = new EquatableCollectionContains($value);

        $this->assertFalse($constraint->evaluate($array, '', true));
    }
}
