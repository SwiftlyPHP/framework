<?php

namespace Swiftly\Routing;

/**
 * Interface for classes that can parse the Swiftly route format
 *
 * @author clvarley
 */
Interface ParserInterface
{

    /**
     * Parse the routes file and return the routes
     *
     * @param string $filename Routes file
     * @return Route[]         Parsed routes
     */
    public function parseFile( string $filename ) : array;

}
