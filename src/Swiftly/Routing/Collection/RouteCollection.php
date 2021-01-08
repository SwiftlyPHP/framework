<?php

namespace Swiftly\Routing\Collection;

use Swiftly\Routing\{
    CollectionInterface,
    Route
};

use function current;
use function key;
use function next;
use function reset;
use function count;
use function in_array;
use function implode;

/**
 * Class used to store and manage a collection of routes
 *
 * @author clvarley
 */
Class RouteCollection Implements CollectionInterface
{

    /**
     * The contents of this collection
     *
     * @var Route[] $routes Route collection
     */
    protected $routes;

    /**
     * Create a new collection around the given routes
     *
     * @param Route[] $routes (Optional) Route definitions
     */
    public function __construct( array $routes = [] )
    {
        $this->routes = $routes;
    }

    /**
     * Adds a new route to the collection
     *
     * @param string $name Route identifier
     * @param Route $route Route definition
     * @return void        N/a
     */
    public function add( string $name, Route $route ) : void
    {
        $this->routes[$name] = $route;
    }

    /**
     * Gets the named route from the collection
     *
     * @param string $name Route identifier
     * @return Route|null  Route definition
     */
    public function get( string $name ) : ?Route
    {
        return $this->routes[$name] ?? null;
    }

    /**
     * Removes the named route from the collection
     *
     * @param string $name Route identifier
     * @return void        N/a
     */
    public function remove( string $name ) : void
    {
        unset( $this->routes[$name] );
    }

    /**
     * Returns the current route
     *
     * @return Route Current route
     */
    public function current() : Route
    {
        return current( $this->routes );
    }

    /**
     * Returns the current route name
     *
     * @return string Route name
     */
    public function key() : string
    {
      return key( $this->routes );
    }

    /**
     * Move the pointer to the next element
     *
     * @return void N/a
     */
    public function next() : void
    {
        next( $this->routes );
    }

    /**
     * Reset the pointer to the first element
     *
     * @return void N/a
     */
    public function rewind() : void
    {
        reset( $this->routes );
    }

    /**
     * Check if the current position is valid
     *
     * @return bool Valid position
     */
    public function valid() : bool
    {
        return current( $this->routes ) !== false;
    }

    /**
     * Returns the number of routes in this collection
     *
     * @return int Route count
     */
    public function count() : int
    {
        return count( $this->routes );
    }

    /**
     * Compiles the regex for the given HTTP method
     *
     * @param string $method (Optional) HTTP method
     * @return string        Compiled regex
     */
    public function compile( string $method = 'GET' ) : string
    {
        $regexes = [];

        foreach ( $this->routes as $name => $route ) {
            if ( in_array( $method, $route->methods ) ) {
                $regexes[] = '(?>' . $route->regex . '(*:' . $name . '))';
            }
        }

        return '~^(?|' . implode( '|', $regexes ) . ')$~ixX';
    }
}
