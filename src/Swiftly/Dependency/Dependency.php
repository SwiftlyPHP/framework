<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\Container;

/**
 * Wraps a dependency in the dependency container
 *
 * @author C Varley <clvarley>
 */
Class Dependency
{

    /**
     * The parent container for this dependency
     *
     * @var Container $container Dependency container
     */
    private $container;

    /**
     * Actual implementation of this dependency
     *
     * @var callable|object $implementation Dependency implementation
     */
    private $implementation;

    /**
    * Is this dependency a singleton?
    *
    * @var bool $singleton Is singleton?
    */
    private $singleton = false;

    /**
     * Creates a new dependency
     *
     * @param callable|object $implementation Implementation
     * @param Container $container            Dependency container
     */
    public function __construct( $implementation, Container $container )
    {
        $this->implementation = $implementation;
        $this->container = $container;
    }

    /**
     * Sets whether or not this dependency is a singleton
     *
     * @param bool $singleton   Is singleton?
     * @return self             Allow chaining
     */
    public function singleton( bool $singleton ) : self
    {
        $this->singleton = $singleton;

        return $this;
    }

    /**
     * Sets an alias for this dependency
     *
     * @param string $name  Dependency alias
     * @return self         Allow chaining
     */
    public function alias( string $name ) : self
    {
        $this->container->alias( $name, $this );

        return $this;
    }

    /**
     * Resolve this dependency
     *
     * @return object Resolved dependency
     */
    public function resolve() /* : object */
    {
        $result = null;

        if ( \is_callable( $this->implementation ) ) {
            $callback = $this->implementation;
            $result = $callback( $this->container );
        } elseif ( \is_object( $this->implementation ) ) {
            $result = $this->implementation;
        } elseif ( \is_string( $this->implementation ) && \class_exists( $this->implementation ) ) {
            $result = $this->initialize( $this->implementation );
        }

        if ( $this->singleton ) {
            $this->implementation = $result;
            $this->singleton = false;
        }

        return $result;
    }

    /**
     * Resolves arameters of an object constructor and creates an object
     *
     * @param string $class Class name
     * @return object       Initialized object
     */
    private function initialize( string $class ) /* :object */
    {
        $constructor = ( new \ReflectionClass( $class ) )->getConstructor();

        // No constructor
        if ( empty( $constructor ) ) {
            return ( new $class );
        }

        // TODO: Needs a tidy

        $arguments = [];

        foreach ( $constructor->getParameters() as $param ) {
            $value = null;

            $type = $param->getType();

            if ( !$type->isBuiltin() ) {
                $value = $this->container->resolve( $type->getName() );
            }

            if ( $value === null && $param->isOptional() ) {
                $value = $param->getDefaultValue();
            }

            $arguments[$param->getPosition()] = $value;
        }

        return ( new $class( ...$arguments ) );
    }
}
