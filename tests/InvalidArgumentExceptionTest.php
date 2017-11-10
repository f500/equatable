<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests;

use F500\Equatable\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_an_invalidType_exception_mentioning_the_type_of_a_scalar_value()
    {
        $exception = InvalidArgumentException::invalidType('someArgument', 'string', true);

        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertSame(
            'Argument $someArgument must be of type string, boolean given',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_an_invalidType_exception_mentioning_the_type_of_an_object()
    {
        $exception = InvalidArgumentException::invalidType('someArgument', 'string', new stdClass());

        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertSame(
            'Argument $someArgument must be of type string, stdClass given',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_an_invalidTypeInArray_exception_mentioning_the_type_of_a_scalar_value()
    {
        $exception = InvalidArgumentException::invalidTypeInArray('someArgument', 'string', true);

        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertSame(
            'Each value in argument $someArgument must be of type string, boolean given',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_an_invalidTypeInArray_exception_mentioning_the_type_of_an_object()
    {
        $exception = InvalidArgumentException::invalidTypeInArray('someArgument', 'string', new stdClass());

        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertSame(
            'Each value in argument $someArgument must be of type string, stdClass given',
            $exception->getMessage()
        );
    }
}
