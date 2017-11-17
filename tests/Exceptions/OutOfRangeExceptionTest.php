<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests\Exceptions;

use F500\Equatable\Exceptions\OutOfRangeException;
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
    public function it_creates_a_doesNotContainIndex_exception()
    {
        $exception = OutOfRangeException::doesNotContainIndex(0);

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the index 0',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainKey_exception()
    {
        $exception = OutOfRangeException::doesNotContainKey('a');

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the key "a"',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainValue_exception_mentioning_an_integer()
    {
        $exception = OutOfRangeException::doesNotContainValue(123);

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain the value integer(123)',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainValue_exception_mentioning_a_string()
    {
        $exception = OutOfRangeException::doesNotContainValue('Some value');

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            "Collection does not contain the value string('Some value')",
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainValue_exception_mentioning_an_object()
    {
        $exception = OutOfRangeException::doesNotContainValue(new EquatableObject('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertRegExp(
            '/Collection does not contain the value F500\\\\Equatable\\\\Tests\\\\Objects\\\\EquatableObject\([0-9a-z]{32}\)/',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainValue_exception_mentioning_a_toString_object()
    {
        $exception = OutOfRangeException::doesNotContainValue(new EquatableObjectWithToString('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            "Collection does not contain the value F500\\Equatable\\Tests\\Objects\\EquatableObjectWithToString('Some value')",
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainValue_exception_mentioning_a_magic_toString_object()
    {
        $exception = OutOfRangeException::doesNotContainValue(new EquatableObjectWithMagicToString('Some value'));

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            "Collection does not contain the value F500\\Equatable\\Tests\\Objects\\EquatableObjectWithMagicToString('Some value')",
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function it_creates_a_doesNotContainAnything_exception()
    {
        $exception = OutOfRangeException::doesNotContainAnything();

        $this->assertInstanceOf(OutOfRangeException::class, $exception);
        $this->assertSame(
            'Collection does not contain anything',
            $exception->getMessage()
        );
    }
}
