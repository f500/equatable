<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\PHPUnit\Constraint;

use F500\Equatable\Equatable;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class IsEqual extends \PHPUnit_Framework_Constraint_IsEqual
{
    /**
     * @inheritdoc
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        if ($this->value === $other) {
            return true;
        }

        if ($this->value instanceof Equatable) {
            return $this->handleResult(
                $this->value,
                $other,
                $this->value->equals($other),
                $description,
                $returnResult
            );
        }

        if ($other instanceof Equatable) {
            return $this->handleResult(
                $this->value,
                $other,
                $other->equals($this->value),
                $description,
                $returnResult
            );
        }

        return parent::evaluate($other, $description, $returnResult);
    }

    /**
     * @param mixed  $expected
     * @param mixed  $actual
     * @param bool   $result
     * @param string $description
     * @param bool   $returnResult
     *
     * @return bool
     */
    private function handleResult($expected, $actual, $result, $description, $returnResult)
    {
        if ($result) {
            return true;
        }

        if ($returnResult) {
            return false;
        }

        $comparisonFailure = new ComparisonFailure(
            $expected,
            $actual,
            $this->exporter->export($expected),
            $this->exporter->export($actual),
            false,
            'Failed asserting that two equatable objects are equal.'
        );

        throw new ExpectationFailedException(
            trim($description . "\n" . $comparisonFailure->getMessage()),
            $comparisonFailure
        );
    }
}
