<?php

namespace Swiftly\Routing;

use Swiftly\Routing\{
    Route,
    ParserInterface,
    CollectionInterface
};

use function rtrim;
use function in_array;
use function preg_match_all;

use const PREG_SET_ORDER;

/**
 * Simple regex dispatcher
 *
 * @author clvarley
 */
Class Dispatcher
{

    /**
     * The route file parser to use
     *
     * @var ParserInterface $parser Route parser
     */
    private $parser;

    /**
     * Collection of routes
     *
     * @var CollectionInterface|null $routes Route collection
     */
    private $routes = null;

    /**
     * HTTP methods supported by this router
     *
     * @var string[] ALLOWED_METHODS HTTP methods
     */
    private const ALLOWED_METHODS = [
        'OPTIONS',
        'HEAD',
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    /**
     * Create a new router specifying the parser to use
     *
     * @param ParserInterface $parser Route parser
     */
    public function __construct( ParserInterface $parser )
    {
        $this->parser = $parser;
    }

    /**
     * Gets all the registered routes
     *
     * @return CollectionInterface|null Route definitions
     */
    public function getRoutes() : ?CollectionInterface
    {
        return $this->routes;
    }

    /**
     * Loads the given routes file
     *
     * @var string $filename File path
     */
    public function load( string $filename ) : void
    {
        $this->routes = $this->parser->parse( $filename );
    }

    /**
     * Returns all the routes that match the path
     *
     * @param string $method HTTP method
     * @param string $path   URL path
     * @return Route|null    Route definition
     */
    public function dispatch( string $method, string $path ) : ?Route
    {
        $path = rtrim( $path, " \n\r\t\0\x0B\\/" );

        if ( empty( $path ) ) {
            $path = '/';
        }

        if ( !in_array( $method, self::ALLOWED_METHODS ) ) {
            $method = 'GET';
        }

        // Compile the regex
        $regex = $this->routes->compile( $method );

        if ( !preg_match_all( $regex, $path, $matches, PREG_SET_ORDER ) ) {
            return null;
        }

        // Get the named route
        $route = $this->routes->get( $matches[0]['MARK'] );

        // Handle params (if any)
        $args = [];

        foreach ( $route->args as $index => $param ) {
            $args[$param] = $matches[0][$index + 1] ?? null;
        }

        $route->args = $args;

        return $route;
    }
}
