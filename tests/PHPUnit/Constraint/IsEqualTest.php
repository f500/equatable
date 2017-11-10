<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests\PHPUnit\Constraint;

use F500\Equatable\PHPUnit\Constraint\IsEqual;
use F500\Equatable\Tests\Objects\EquatableObject;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class IsEqualTest extends TestCase
{
    /**
     * @test
     */
    public function it_evaluates_to_true_when_both_are_equatable_and_equal()
    {
        $value = new EquatableObject('foo');
        $other = new EquatableObject('foo');

        $constraint = new IsEqual($value);

        $this->assertTrue($constraint->evaluate($other));
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that two equatable objects are equal
     */
    public function it_fails_to_evaluate_when_only_value_is_equatable()
    {
        $value = new EquatableObject('foo');
        $other = 'bar';

        $constraint = new IsEqual($value);

        $constraint->evaluate($other);
    }

    /**
     * @test
     */
    public function it_evaluates_to_false_when_only_value_is_equatable()
    {
        $value = new EquatableObject('foo');
        $other = 'bar';

        $constraint = new IsEqual($value);

        $this->assertFalse($constraint->evaluate($other, '', true));
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that two equatable objects are equal
     */
    public function it_fails_to_evaluate_when_only_other_is_equatable()
    {
        $value = 'foo';
        $other = new EquatableObject('bar');

        $constraint = new IsEqual($value);

        $constraint->evaluate($other);
    }

    /**
     * @test
     */
    public function it_evaluates_to_false_when_only_other_is_equatable()
    {
        $value = 'foo';
        $other = new EquatableObject('bar');

        $constraint = new IsEqual($value);

        $this->assertFalse($constraint->evaluate($other, '', true));
    }

    /**
     * @test
     */
    public function it_returns_early_when_identical()
    {
        $value = new EquatableObject('foo');
        $other = $value;

        $constraint = new IsEqual($value);

        $this->assertTrue($constraint->evaluate($other));
    }

    /**
     * @test
     */
    public function it_falls_back_to_parent_when_not_equatable()
    {
        $value = new \stdClass();
        $other = new \stdClass();

        $constraint = new IsEqual($value);

        $this->assertTrue($constraint->evaluate($other));
    }
}
