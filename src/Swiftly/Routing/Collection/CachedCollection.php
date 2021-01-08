<?php

namespace Swiftly\Routing\Collection;

use Swiftly\Routing\CollectionInterface;

/**
 * Class used to store a collection of cached routes
 *
 * @author clvarley
 */
Class CachedCollection Implements CollectionInterface
{

    /**
     * Precompiled route regexes
     *
     * @var string[] $compiled Compiled regexes
     */
    protected $compiled;

    /**
     * Create a new collection wrapping pre-compiled regexes
     *
     * @param string[] $compiled Compiled regexes
     */
    public function __construct( array $compiled = [] )
    {
        $this->compile = $compiled;
    }

    /**
     * Returns the pre-compiled regex for the given HTTP method
     *
     * @param string $method (Optional) HTTP method
     * @return string        Compiled regex
     */
    public function compile( string $method = 'GET' ) : string
    {
        return ( isset( $this->compiled[$method] )
            ? $this->compiled[$method]
            : ''
        );
    }
}
