<?php

/**
 * @license https://github.com/f500/equatable/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace F500\Equatable\Tests\Exceptions;

use F500\Equatable\Exceptions\InRangeException;
use PHPUnit\Framework\TestCase;

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
        $exception = InRangeException::keyInRange('a');

        $this->assertInstanceOf(InRangeException::class, $exception);
        $this->assertSame(
            'Collection already contains the key "a"',
            $exception->getMessage()
        );
    }
}
