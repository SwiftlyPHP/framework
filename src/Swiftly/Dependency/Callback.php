<?php

namespace Swiftly\Dependency;

/**
 * Provides utilities for dealing with callback variables
 *
 * @author clvarley
 */
Class Callback
{

    /**
     * Indicates the callback is not valid
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
     * Indicates the callback is a static method
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
     * Attempts to infer the type of given callback
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

        // Class with __invoke magic method?
        if ( \is_object( $callable ) && \is_callable( $callable ) ) {
            return self::TYPE_INVOKABLE;
        }

        // Valid method or static method call?
        if ( !\is_array( $callable ) || \count( $callable ) !== 2 ) {
            return self::TYPE_INVALID;
        }

        list( $class, $method ) = $callable;

        // Method must be a string!
        if ( !\is_string( $method ) ) {
            return self::TYPE_INVALID;
        }

        // Object or class name!
        if ( !\is_string( $class ) && !\is_object( $class ) ) {
            return self::TYPE_INVALID;
        }

        // Class method or static function?
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
}
