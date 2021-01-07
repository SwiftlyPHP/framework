<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\{
    LoaderInterface,
    Dependency
};

/**
 * Dependency management
 *
 * @author C Varley <clvarley>
 */
Class Container
{

    /**
     * The service implementations
     *
     * @var Dependency[] $services Array of implementations
     */
    private $services = [];

    /**
     * Binds a new service by name
     *
     * @param string $name    Service name
     * @param callable|object Service implementation
     * @return Dependency     Dependency wrapper
     */
    public function bind( string $name, $implementation ) : Dependency
    {
        $this->services[$name] = new Dependency( $implementation, $this );

        return $this->services[$name];
    }

    /**
     * Used to assign an alias to a dependency
     *
     * @internal
     *
     * @param string $name           Service name
     * @param Dependency $dependency Dependency wrapper
     * @return Dependency            Dependency wrapper
     */
    public function alias( string $name, Dependency $dependency ) : Dependency
    {
        $this->services[$name] = $dependency;

        return $dependency;
    }

    /**
     * Load services from the given dependency loader
     *
     * @param LoaderInterface $loader Dependency loader
     * @return void                   N/a
     */
    public function load( LoaderInterface $loader ) : void
    {
        $loader->load( $this );
    }

    /**
     * Resolve the given dependency
     *
     * @param string $name Service name
     * @return object|null Service implementation
     */
    public function resolve( string $name ) /* : ?object */
    {
        $result = null;

        if ( isset( $this->services[$name] ) ) {
            $result = $this->services[$name]->resolve( $this );
        }

        return $result;
    }

    /**
     * Check to see if a service exists
     *
     * @param string $name  Service name
     * @return bool         Service exists
     */
    public function has( string $name ) : bool
    {
        return isset( $this->services[$name] );
    }
}
