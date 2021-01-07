<?php

namespace Swiftly\Http;

/**
 * Utility container for managing HTTP headers
 *
 * @author clvarley
 */
Class Headers
{

    /**
     * Array of HTTP headers
     *
     * @var array[] $headers Http headers
     */
    protected $headers = [];

    /**
     * Creates a new header bag from the (optionally) provided headers
     *
     * @param array $headers (Optional) Http headers
     */
    public function __construct( array $headers = [] )
    {
        foreach ( $headers as $name => $value ) {
            $this->set( $name, $value, false );
        }
    }

    /**
     * Sets a single value for the named HTTP header
     *
     * @param string $name  Header name
     * @param string $value Header value
     * @param bool $replace (Optional) Replace existing
     * @return void         N/a
     */
    public function set( string $name, string $value, bool $replace = true ) : void
    {
        $name = \strtolower( $name );

        if ( $replace || !isset( $this->headers[$name] ) ) {
            $this->headers[$name] = [ $value ];
        } else {
            $this->headers[$name][] = $value;
        }

        return;
    }

    /**
     * Gets a single value for the named HTTP header
     *
     * @param string $name Header name
     * @return string|null Header value
     */
    public function get( string $name ) : ?string
    {
        $name = \strtolower( $name );

        return $this->headers[$name][0] ?? null;
    }

    /**
     * Checks to see if a named HTTP header has been set
     *
     * @param string $name Header name
     * @return bool        Header set?
     */
    public function has( string $name ) : bool
    {
        $name = \strtolower( $name );

        return \array_key_exists( $name, $this->headers );
    }

    /**
     * Gets all the values for the named HTTP header
     *
     * If no header name is provided, all values for all HTTP headers are
     * returned.
     *
     * @param string|null $name (Optional) Header name
     * @return array            Header values
     */
    public function all( string $name = null ) : array
    {
        if ( $name === null ) {
            return $this->headers;
        }

        $name = \strtolower( $name );

        return $this->headers[$name] ?? [];
    }
}
