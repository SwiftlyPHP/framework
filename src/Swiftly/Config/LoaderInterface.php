<?php

namespace Swiftly\Config;

use Swiftly\Config\Config;

/**
 * Interface for classes that can load config values.
 *
 * @author clvarley
 */
Interface LoaderInterface
{

    /**
     * Load values into the given config
     *
     * @param Config $config Config object
     * @return void          Config object
     */
    public function load( Config $config ) : Config;

}
