<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\Container;

/**
 *
 */
Class Resolver
{

    /**
     * Reference to the main dependency container
     *
     * @var Container $container Dependency container
     */
    protected $container;

    /**
     *
     */
    public function __construct( Container $container )
    {
        $this->container = $container;
    }

    /**
     *
     */
    public function call( callable $callback ) // : mixed
    {
        // TODO:

        return;
    }
}
