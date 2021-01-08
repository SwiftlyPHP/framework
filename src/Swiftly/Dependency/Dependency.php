<?php

namespace Swiftly\Dependency;

use Swiftly\Dependency\Callback;
use Swiftly\Dependency\Container;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionClass;
use ReflectionFunctionAbstract;

use function array_merge;
use function is_object;
use function is_string;
use function class_exists;
use function call_user_func_array;

/**
 * Wraps a dependency in the dependency container
 *
 * @author clvarley
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
     * Handler used to construct this dependency
     *
     * @var callable|string $handler Dependency handler
     */
    private $handler;

    /**
     * Function arguments to be used during dependency resolution
     *
     * @var mixed[] $arguments Resolution arguments
     */
    private $arguments = [];

    /**
     * Cached resolved dependency
     *
     * @var object|null $resolved Resolved dependency
     */
    private $resolved = null;

    /**
     * Is this dependency a singleton?
     *
     * @var bool $singleton Is singleton?
     */
    private $singleton = true;

    /**
     * Post resolution callbacks
     *
     * @var callable[] $callbacks Callbacks
     */
    private $callbacks = [];

    /**
     * Creates a new dependency
     *
     * @param callable|object $handler Dependency handler
     * @param Container $container     Dependency container
     */
    public function __construct( $handler, Container $container )
    {
        $this->handler = $handler;
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
     * Sets named arguments to be used during resolution
     *
     * @param array $arguments Constructor arguments
     * @return self            Allow chaining
     */
    public function arguments( array $arguments ) : self
    {
        $this->arguments = array_merge(
            $this->arguments,
            $arguments
        );

        return $this;
    }

    /**
     * Register callback to be run after service resolution
     *
     * @param callable $callback Callback
     * @return self              Allow chaining
     */
    public function then( callable $callback ) : self
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * Attempt to resolve this dependency
     *
     * @return object|null Resolved dependency
     */
    public function resolve() // : ?object
    {
        // Already instantiated?
        if ( is_object( $this->resolved ) && $this->singleton ) {
            return $this->resolved;
        }

        // Might just be a class name?
        if ( is_string( $this->handler ) && class_exists( $this->handler ) ) {
            $this->resolved = $this->constructClass( $this->handler );
            return $this->resolved;
        }

        // Try to figure it out!
        switch ( Callback::inferType( $this->handler ) ) {
            case Callback::TYPE_CLOSURE:
            case Callback::TYPE_FUNCTION:
            case Callback::TYPE_INVOKABLE:
                $this->resolved = $this->callFunction( $this->handler );
                break;

            case Callback::TYPE_STATIC:
                $this->resolved = $this->callStatic( ...$this->handler );
                break;

            case Callback::TYPE_METHOD:
                $this->resolved = $this->callMethod( ...$this->handler );
                break;

            case Callback::TYPE_INVALID:
            default:
                // Throw maybe?
                if ( is_object( $this->handler ) ) {
                    $this->resolved = $this->handler;
                }
                break;
        }

        // TODO: Make the callback resolvable too!
        foreach ( $this->callbacks as $callback ) {
            $callback( $this->resolved );
        }

        return $this->resolved;
    }

    /**
     * Attempts to resolve and call a standard function
     *
     * @param callable $function Function
     * @return void              Function return value
     */
    public function callFunction( callable $function ) // : mixed
    {
        $reflected = new ReflectionFunction( $function );

        $arguments = $this->reflect( $reflected );

        return call_user_func_array( $function, $arguments );
    }

    /**
     * Attempts to resolve and call a class method
     *
     * @param object $class  Class object
     * @param string $method Method name
     * @return mixed         Method return value
     */
    public function callMethod( /* object */ $class, string $method ) // : mixed
    {
        if ( !is_object( $class ) ) {
            $class = $this->constructClass( $class );
        }

        $function = new ReflectionMethod( $class, $method );

        $arguments = $this->reflect( $function );

        return call_user_func_array([ $class, $method ], $arguments );
    }

    /**
     * Attempts to resolve and call a static method
     *
     * @param string $class  Class name
     * @param string $method Method name
     * @return mixed         Static return value
     */
    public function callStatic( string $class, string $method ) // : mixed
    {
        $function = new ReflectionMethod( $class, $method );

        $arguments = $this->reflect( $function );

        return call_user_func_array([ $class, $method ], $arguments );
    }

    /**
    * Attempts to construct the given class
    *
    * @param string $class Class name
    * @return object|null  Instantiated object
    */
    public function constructClass( string $class ) // : ?object
    {
        $reflected = new ReflectionClass( $class );
        $constructor = $reflected->getConstructor();

        if ( $constructor !== null ) {
            $arguments = $this->reflect( $constructor );
        } else {
            $arguments = [];
        }

        return $reflected->newInstanceArgs( $arguments );
    }

    /**
     * Returns the arguments for this function/method
     *
     * @param ReflectionFunctionAbstract $reflection Reflected function
     * @return array                                 Function arguments
     */
    private function reflect( ReflectionFunctionAbstract $reflection ) : array
    {
        $arguments = [];

        foreach ( $reflection->getParameters() as $parameter ) {
            $type = $parameter->getType();
            $name = $parameter->getName();

            if ( $type === null || $type->isBuiltin() ) {
                $value = $this->arguments[$name] ?? null;
            } else {
                $value = $this->container->resolve( $type->getName() );
            }

            // Backwards compat for older versions!
            if ( $name === 'app' ) {
                $value = $this->container;
            }

            // One last try for a value
            if ( $value === null && $parameter->isDefaultValueAvailable() ) {
                $value = $parameter->getDefaultValue();
            }

            $arguments[$parameter->getPosition()] = $value;
        }

        return $arguments;
    }
}
