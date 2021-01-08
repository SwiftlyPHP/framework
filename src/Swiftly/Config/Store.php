<?php

namespace Swiftly\Config;

use Swiftly\Config\LoaderInterface;

use function strtolower;
use function array_key_exists;

/**
 * Container used to store and manipulate config values
 *
 * @author clvarley
 */
Class Store
{

    /**
     * Array of config values
     *
     * Assumed to be a multidimensional array in format <string, mixed>.
     *
     * @var array $values Config values
     */
    protected $values;

    /**
     * Creates a new config from the (optionally) provided values
     *
     * @param array $values Config values
     */
    public function __construct( array $values = [] )
    {
        $this->values = $values;
    }

    /**
     * Get config values from the given loader
     *
     * @param LoaderInterface $loader Config loader
     * @return self                   For chaining
     */
    public function load( LoaderInterface $loader ) : Store
    {
        return $loader->load( $this );
    }


    /**
     * Gets the value for the given setting
     *
     * If no value is found, the provided $default will be returned instead.
     *
     * @param string $key    Setting name
     * @param mixed $default (Optional) Default value
     * @return mixed         Setting value
     */
    public function get( string $key, $default = null ) // : mixed
    {
        $key = strtolower( $key );

        return ( array_key_exists( $key, $this->values )
            ? $this->values[$key]
            : $default
        );
    }

    /**
     * Sets the value for the given setting
     *
     * @param string $key  Setting name
     * @param mixed $value Setting value
     * @return void        N/a
     */
    public function set( string $key, $value ) : void
    {
        $key = strtolower( $key );
        $this->values[$key] = $value;

        return;
    }

    /**
     * Checks to see if the given setting has a value
     *
     * @param string $key Setting name
     * @return bool       Has value?
     */
    public function has( string $key ) : bool
    {
        return array_key_exists( $key, $this->values );
    }
}
