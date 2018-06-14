<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\PHPUnit\Constraint;

use F500\Equatable\Equatable;
use PHPUnit\Framework\Constraint\IsEqual as BaseIsEqual;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class IsEqual extends BaseIsEqual
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @inheritdoc
     */
    public function __construct(
        $value,
        float $delta = 0.0,
        int $maxDepth = 10,
        bool $canonicalize =
        false,
        bool $ignoreCase = false
    ) {
        parent::__construct($value, $delta, $maxDepth, $canonicalize, $ignoreCase);

        $this->value = $value;
    }

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
