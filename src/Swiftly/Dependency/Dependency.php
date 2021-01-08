<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\Container;
use Closure;

/**
 * Wraps a dependency in the dependency container
 *
 * @author clvarley
 */
Class Dependency
{

    /**
     * Indicates the callback is invalid
     *
     * @var int TYPE_INVALID Invalid callback
     */
    const TYPE_INVALID = 0;

    /**
     * Indicates the callback is a standard function
     *
     * @var int TYPE_FUNCTION Standard function
     */
    const TYPE_FUNCTION = 1;

    /**
     * Indicates the callback is a class method
     *
     * @var int TYPE_METHOD Class method
     */
    const TYPE_METHOD = 2;

    /**
     * Indicates the callback is a static function
     *
     * @var int TYPE_STATIC Static function
     */
    const TYPE_STATIC = 3;

    /**
     * Indicates the callback is an invokable object
     *
     * @var int TYPE_INVOKABLE Invokable object
     */
    const TYPE_INVOKABLE = 4;

    /**
     * Indicates the callback is a closure
     *
     * @var int TYPE_CLOSURE Closure function
     */
    const TYPE_CLOSURE = 5;

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
     * Attempts to infer the type of given callable
     *
     * @param mixed $callable Callable variable
     * @return int            TYPE_* constant
     */
    public static function inferType( $callable ) : int
    {
        // Nice and easy!
        if ( $callable instanceof Closure ) {
            return self::TYPE_CLOSURE;
        }

        // Function name or possible static call?
        if ( \is_string( $callable ) ) {
            if ( \strpos( $callable, '::' ) === false ) {
                return self::TYPE_FUNCTION;
            }

            $callable = \explode( '::', $callable );
        }

        // Neither a static or method call?
        if ( !\is_array( $callable ) || \count( $callable ) !== 2 ) {
            return self::TYPE_INVALID;
        }

        list( $class, $method ) = $callable;

        // Has to be a string by this point
        if ( !\is_string( $method ) ) {
            return self::TYPE_INVALID;
        }

        if ( \is_object( $class ) ) {
            return self::TYPE_METHOD;
        }

        // Static method or just uninitialised object?
        try {
            $reflected = new \ReflectionMethod( $class, $method );
        } catch ( \ReflectionException $e ) {
            return self::TYPE_INVALID;
        }

        return ( $reflected->isStatic()
            ? self::TYPE_STATIC
            : self::TYPE_METHOD
        );
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
