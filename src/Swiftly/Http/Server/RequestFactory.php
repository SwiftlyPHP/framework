<?php

namespace Swiftly\Http\Server;

use \Swiftly\Http\{
    Headers,
    Parameters,
    Url
};

/**
 * Factory used to create Request objects
 *
 * @author clvarley
 */
Class RequestFactory
{

    /**
     * Creates a Request object from the values provided
     *
     * @param string $method HTTP method
     * @param string $url    Request URL
     * @param array $headers HTTP headers
     * @param array $query   Query parameters
     * @param array $post    POST parameters
     * @return Request       Request object
     */
    public function create( string $method = 'GET', string $url = '', array $headers = [], array $query = [], array $post = [] ) : Request
    {
        return new Request(
            $method,
            Url::fromString( $url ),
            new Headers( $headers ),
            new Parameters( $query ),
            new Parameters( $post )
        );
    }

    /**
     * Creates a Request object from the global request values
     *
     * @global array $_SERVER
     * @return Request Request object
     */
    public function fromGlobals() : Request
    {
        return new Request(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            Url::fromString( $this->getAbsoluteUrl() ),
            new Headers( \getallheaders() ),
            new Parameters( $_GET ),
            new Parameters( $_POST )
        );
    }

    /**
     * Returns the absolute URL for the request
     *
     * @global array $_SERVER
     * @return string Absolute URL
     */
    private function getAbsoluteUrl() : string
    {
        // Get the connection protocol
        if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) {
            $scheme = 'https';
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }

        return "$scheme://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }
}
