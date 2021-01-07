<?php

namespace Swiftly\Config;

/**
 * Represents a collection of config values
 *
 * @author clvarley
 */
Class Config
{

    /**
     * Array of config settings
     *
     * @var array $settings Config settings
     */
    private $settings;

    /**
     * Construct config object
     *
     * @param array $settings Config settings
     */
    public function __construct( array $settings = [] )
    {
        $this->settings = $settings;
    }

    /**
     * Sets the value for the given setting
     *
     * @param string $name Setting name
     * @param mixed $value Setting value
     * @return void        N/a
     */
    public function set( string $name, /* mixed */ $value ) : void
    {
        $name = \strtolower( $name );

        $this->settings[$name] = $value;

        return;
    }

    /**
     * Gets the value for the given setting
     *
     * If no value is found, the provided `$default` will be returned instead
     *
     * @param string $name   Setting name
     * @param mixed $default (Optional) Default value
     * @return mixed         Setting value
     */
    public function get( string $name, /* mixed */ $default = null ) // : mixed
    {
        $name = \strtolower( $name );

        return ( \array_key_exists( $name, $this->settings )
            ? $this->settings[$name]
            : $default
        );
    }

    /**
     * Checks to see if the given setting has a value
     *
     * @param string $name Setting name
     * @return bool        Has value?
     */
    public function has( string $name ) : bool
    {
        $name = \strtolower( $name );

        return \array_key_exists( $name, $this->settings );
    }
}
