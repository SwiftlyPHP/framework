<?php

namespace Swiftly\Framework\Tests\Utilities;

use PHPUnit\Framework\TestCase;

use function format_bytes;

Class FormatBytesTest Extends TestCase
{

    const KB_IN_BYTES = 1024;
    const MB_IN_BYTES = self::KB_IN_BYTES * 1024;
    const GB_IN_BYTES = self::MB_IN_BYTES * 1024;
    const TB_IN_BYTES = self::GB_IN_BYTES * 1024;

    public function testFunctionCanFormatWholeValues() : void
    {
        self::assertSame( '1.00 tb', format_bytes( self::TB_IN_BYTES ) );
        self::assertSame( '1.00 gb', format_bytes( self::GB_IN_BYTES ) );
        self::assertSame( '1.00 mb', format_bytes( self::MB_IN_BYTES ) );
        self::assertSame( '1.00 kb', format_bytes( self::KB_IN_BYTES ) );
    }

    public function testFunctionCanFormatFractionalValues() : void
    {
        self::assertSame( '1.50 gb', format_bytes( self::GB_IN_BYTES * 1.5 ) );
        self::assertSame( '1.33 mb', format_bytes( self::MB_IN_BYTES * 1.33 ) );
        self::assertSame( '1.25 kb', format_bytes( self::KB_IN_BYTES * 1.25 ) );
        self::assertSame( '1.20 gb', format_bytes( self::GB_IN_BYTES * 1.2 ) );
        self::assertSame( '1.17 mb', format_bytes( self::MB_IN_BYTES * ( 7 / 6 )));
        self::assertSame( '1.14 kb', format_bytes( self::KB_IN_BYTES * ( 8 / 7 )));
    }
}
