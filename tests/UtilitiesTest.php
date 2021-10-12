<?php

namespace Swiftly\Framework\Tests;

use PHPUnit\Framework\TestCase;
use SplFixedArray;
use SplQueue;

use function array_satisfies;
use function format_bytes;

Class UtilitiesTest Extends TestCase
{

    const KB_IN_BYTES = 1024;
    const MB_IN_BYTES = self::KB_IN_BYTES * 1024;
    const GB_IN_BYTES = self::MB_IN_BYTES * 1024;
    const TB_IN_BYTES = self::GB_IN_BYTES * 1024;

    public function testArraySatisfiesReturnsCorrectValues() : void
    {
        self::assertTrue( array_satisfies( [1, 2, 3], 'is_int' ) );
        self::assertFalse( array_satisfies( [1, 2, '3'], 'is_int' ) );
        self::assertTrue( array_satisfies( [1, 2.3, '3'], 'is_numeric' ) );
    }

    public function testArraySatisfiesPassesCorrectValues() : void
    {
        $subject = [new SplFixedArray, new SplQueue];

        array_satisfies( $subject, function ( $item ) use (&$subject) {
            static $i = 0;
            self::assertSame( $subject[$i], $item );
            $i++;

            return true;
        });
    }

    public function testFormatBytesFunction() : void
    {
        // Whole values
        self::assertSame( '1.00 tb', format_bytes( self::TB_IN_BYTES ) );
        self::assertSame( '1.00 gb', format_bytes( self::GB_IN_BYTES ) );
        self::assertSame( '1.00 mb', format_bytes( self::MB_IN_BYTES ) );
        self::assertSame( '1.00 kb', format_bytes( self::KB_IN_BYTES ) );

        // Fractionals
        self::assertSame( '1.50 gb', format_bytes( self::GB_IN_BYTES * 1.5 ) );
        self::assertSame( '1.33 mb', format_bytes( self::MB_IN_BYTES * 1.33 ) );
        self::assertSame( '1.25 kb', format_bytes( self::KB_IN_BYTES * 1.25 ) );
        self::assertSame( '1.20 gb', format_bytes( self::GB_IN_BYTES * 1.2 ) );
        self::assertSame( '1.17 mb', format_bytes( self::MB_IN_BYTES * ( 7 / 6 )));
        self::assertSame( '1.14 kb', format_bytes( self::KB_IN_BYTES * ( 8 / 7 )));
    }
}
