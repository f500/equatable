<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests;

use F500\Equatable\OutOfRangeException;
use F500\Equatable\Tests\Objects\EquatableObject;
use F500\Equatable\Tests\Objects\EquatableObjectWithMagicToString;
use F500\Equatable\Tests\Objects\EquatableObjectWithToString;
use PHPUnit\Framework\TestCase;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class OutOfRangeExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_an_indexOutOfRange_exception()
    {
        $exception = OutOfRangeException::indexOutOfRange(0);

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the index 0',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_keyOutOfRange_exception()
    {
        $exception = OutOfRangeException::keyOutOfRange('a');

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the key "a"',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_valueOutOfRange_exception_mentioning_the_type_and_hash_of_an_object()
    {
        $exception = OutOfRangeException::valueOutOfRange(new EquatableObject('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertRegExp(
            '/Collection does not contain the value F500\\\\Equatable\\\\Tests\\\\Objects\\\\EquatableObject\([0-9a-z]{32}\)/',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_valueOutOfRange_exception_mentioning_the_type_and_toString_result_of_an_object()
    {
        $exception = OutOfRangeException::valueOutOfRange(new EquatableObjectWithToString('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the value F500\Equatable\Tests\Objects\EquatableObjectWithToString(Some value)',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_valueOutOfRange_exception_mentioning_the_type_and_magic_toString_result_of_an_object()
    {
        $exception = OutOfRangeException::valueOutOfRange(new EquatableObjectWithMagicToString('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the value F500\Equatable\Tests\Objects\EquatableObjectWithMagicToString(Some value)',
            $exception->getMessage()
        );
    }
}
