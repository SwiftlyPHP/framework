<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\Container;

/**
 * Interface for classes that can load dependencies from a file
 *
 * @author clvarley
 */
Interface LoaderInterface
{

    /**
     * Construct a dependency loader for the given file
     *
     * @param string $filepath  File path
     */
    public function __construct( string $filepath );

    /**
     * Load the dependencies defined in the file into the given container
     *
     * @param Container $container Dependency container
     * @return void                N/a
     */
    public function load( Container $container ) : void;

}
