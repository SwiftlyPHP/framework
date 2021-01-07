<?php

namespace Swiftly\Http;

/**
 * Utility class for managing URLs
 *
 * @author clvarley
 */
Class Url Implements \Stringable
{

    /**
     * The protocol being used
     *
     * @var string $scheme Url scheme
     */
    public $scheme = '';

    /**
     * The requested authority/domain
     *
     * @var string $domain Domain component
     */
    public $domain = '';

    /**
     * Path to resource
     *
     * @var string $path Path component
     */
    public $path = '';

    /**
     * Additional query parameters
     *
     * @var string $query Query string
     */
    public $query = '';

    /**
     * Resource fragment
     *
     * @var string $fragment Fragment identifier
     */
    public $fragment = '';

    /**
     * Returns the string representation of this URL
     *
     * @return string Url
     */
    public function __toString() : string
    {
        $url = "{$this->scheme}://{$this->domain}{$this->path}";

        if ( !empty( $this->query ) ) {
            $url .= "?{$this->query}";
        }

        if ( !empty( $this->fragment ) ) {
            $url .= "#{$this->fragment}";
        }

        return $url;
    }

    /**
     * Attempt to parse the given string into a Url object
     *
     * @param string $url Subject string
     * @return Url|null   Url object
     */
    public static function fromString( string $url ) : ?Url
    {
        $parts = \parse_url( $url );

        if ( empty( $parts ) ) {
            return null;
        }

        $url = new Url;
        $url->scheme   = $parts['scheme']   ?? 'http';
        $url->domain   = $parts['host']     ?? '';
        $url->path     = $parts['path']     ?? '';
        $url->query    = $parts['query']    ?? '';
        $url->fragment = $parts['fragment'] ?? '';

        return $url;
    }
}
