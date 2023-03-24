<?php

namespace Swiftly\Factory;

use Swiftly\Database\AdapterInterface;
use Swiftly\Database\Wrapper;

/**
 * Factory used to create the database wrapper
 * 
 * @since 1.0-beta To reduce clutter in services.php
 */
final class DatabaseWrapperFactory
{
    /**
     * Create a new wrapper around the appropriate database adapter
     */
    public static function create(AdapterInterface $adapter): Wrapper
    {
        $wrapper = new Wrapper($adapter);
        $wrapper->connect();

        return $wrapper;
    }
}
