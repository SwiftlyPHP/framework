<?php

namespace Swiftly\Framework\Tests\Utilities;

use PHPUnit\Framework\TestCase;
use SplFixedArray;
use SplQueue;
use SplDoublyLinkedList;

use function array_satisfies;

Class ArraySatisfiesTest Extends TestCase
{

    public function testFunctionReturnsCorrectValues() : void
    {
        self::assertTrue( array_satisfies( [1, 2, 3], 'is_int' ) );
        self::assertFalse( array_satisfies( [1, 2, '3'], 'is_int' ) );
        self::assertTrue( array_satisfies( [1, 2.3, '3'], 'is_numeric' ) );
    }

    public function testCallbackReceivesCorrectValues() : void
    {
        $subject = [new SplFixedArray, new SplQueue, new SplDoublyLinkedList];

        array_satisfies( $subject, function ( $item ) use (&$subject) {
            static $i = 0;
            self::assertSame( $subject[$i], $item );
            $i++;

            return true;
        });
    }
}
