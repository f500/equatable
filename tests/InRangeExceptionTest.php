<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

namespace F500\Equatable\Tests;

use F500\Equatable\InRangeException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class InRangeExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_keyInRange_exception()
    {
        $exception = InRangeException::keyInRange(0);

        $this->assertInstanceOf(InRangeException::class, $exception);
        $this->assertSame(
            'Collection already contains the key 0',
            $exception->getMessage()
        );
    }
}
