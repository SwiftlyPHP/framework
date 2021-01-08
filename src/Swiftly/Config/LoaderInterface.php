<?php

namespace Swiftly\Config;

use Swiftly\Config\Store;

/**
 * Interface for classes that can parse config files
 *
 * @author clvarley
 */
Interface LoaderInterface
{

    /**
     * Load values into the given config
     *
     * Expected to return the same store object to allow method chaining.
     *
     * @param Store $config Config store
     * @return Store        Updated store
     */
    public function load( Store $config ) : Store;

}
