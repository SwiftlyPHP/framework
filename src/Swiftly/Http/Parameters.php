<?php

namespace Swiftly\Http;

/**
 * Utility container for managing HTTP parameters
 *
 * @author clvarley
 */
Class Parameters
{

    /**
     * Array of HTTP parameters
     *
     * @var array[]
     */
    protected $parameters;

    /**
     * Creates a new parameter bag from the (optionally) provided parameters
     *
     * @param array $parameters (Optional) Http parameters
     */
    public function __construct( array $parameters = [] )
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets the value for the named parameter
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @return void        N/a
     */
    public function set( string $name, /* mixed */ $value ) : void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Gets the value for the named parameter
     *
     * @param string $name Parameter name
     * @return mixed|null  Parameter value
     */
    public function get( string $name ) // : mixed
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * Checks to see if a named parameter has been set
     *
     * @param string $name Parameter name
     * @return bool        Parameter set?
     */
    public function has( string $name ) : bool
    {
        return \array_key_exists( $name, $this->parameters );
    }

    /**
     * Gets all parameters
     *
     * @return array Parameter values
     */
    public function all() : array
    {
        return $this->parameters;
    }

    /**
     * Gets the value for the named parameter as a string
     *
     * If the parameter doesn't exist or cannot be represented as a string, an
     * empty string will be returned instead.
     *
     * @param string $name Parameter name
     * @return string      Parameter value
     */
    public function asString( string $name ) : string
    {
        return ( isset( $this->parameters[$name] ) && \is_scalar( $this->parameters[$name] )
            ? (string)$this->parameters[$name]
            : ''
        );
    }

    /**
     * Gets the value for the named parameter as an integer
     *
     * If the parameter doesn't exist or cannot be represented as an int, the
     * return value will be 0.
     *
     * @param string $name Parameter name
     * @return int         Parameter value
     */
    public function asInt( string $name ) : int
    {
        return ( isset( $this->parameters[$name] ) && \is_numeric( $this->parameters[$name] )
            ? (int)$this->parameters[$name]
            : 0
        );
    }

    /**
     * Gets the value(s) for the named parameter as an array
     *
     * If the parameter doesn't exist or cannot be represented as an array, an
     * empty array will be returned instead.
     *
     * @param string $name Parameter name
     * @return array       Parameter value
     */
    public function asArray( string $name ) : array
    {
        return ( isset( $this->parameters[$name] ) && \is_array( $this->parameters[$name] )
            ? $this->parameters[$name]
            : []
        );
    }
}
