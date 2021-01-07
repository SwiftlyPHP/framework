<?php

namespace Swiftly\Routing;

use \Swiftly\Http\Server\Request;
use \Swiftly\Routing\{ Route, ParserInterface };

/**
 * Handles dispatching of HTTP requests
 *
 * @author clvarley
 */
Class Dispatcher
{

    /**
     * The route file parser to use
     *
     * @var \Swiftly\Routing\ParserInterface $parser Route parser
     */
    private $parser = null;

    /**
     * The compiled regex for each method
     *
     * @var array $compiled Regular expressions
     */
    private $compiled = [];

    /**
     * The routes for this router
     *
     * @var \Swiftly\Routing\Route[] $routes Route definitions
     */
    private $routes = [];

    /**
     * HTTP methods supported by this router
     *
     * @var array ALLOWED_METHODS HTTP methods
     */
    private const ALLOWED_METHODS = [
        'GET',
        'POST',
        'PUT',
        'UPDATE',
        'DELETE'
    ];

    /**
     * Create a new router specifying the parser to use
     *
     * @param \Swiftly\Routing\ParserInterface $parser Route parser
     */
    public function __construct( ParserInterface $parser )
    {
        $this->parser = $parser;
    }

    /**
     * Gets a route definition by name
     *
     * Returns null if the given route does not exist
     *
     * @param  string $name Route name
     * @return Route|null   Route definition
     */
    public function getRoute( string $name ) : ?Route
    {
        return $this->routes[ $name ] ?? null;
    }

    /**
     * Gets all the registered routes
     *
     * @return Route[] Route definitions
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Loads the given routes file
     *
     * @var string $filename  File path
     */
    public function load( string $filename ) : void
    {
        $this->routes = $this->parser->parseFile( $filename );
    }

    /**
     * Returns all the routes that match the path
     *
     * @param \Swiftly\Http\Server\Request $request HTTP request
     * @return \Swiftly\Routing\Action|null         Action
     */
    public function dispatch( Request $request ) : ?Action
    {
        $method = $request->getMethod();

        if ( !\in_array( $method, self::ALLOWED_METHODS ) ) {
            $method = 'GET';
        }

        $path = \rtrim( $request->query->asString( '_route_' ), " \n\r\t\0\x0B\\/" );

        if ( empty( $path ) ) {
            $path = '/';
        }

        // Compile the regex
        if ( !isset( $this->compiled[$method] ) ) {
            $this->compile( $method );
        }

        if ( !\preg_match_all( $this->compiled[$method], $path, $matches, \PREG_SET_ORDER ) ) {
            return null;
        }

        $route = $this->routes[$matches[0]['MARK']];

        $args = [];

        // Handle params (if any)
        foreach ( $route->arguments as $index => $param ) {
            $args[$param['name']] = $matches[0][$index + 1] ?? null;
        }

        return new Action(
            $route->class,
            $route->method,
            $args
        );
    }

    /**
     * Compiles the regex for this method
     *
     * @param string $method  HTTP method
     * @return void           N/a
     */
    private function compile( string $method ) : void
    {
        $regexes = [];

        // Only routes that support the method
        foreach ( $this->routes as $name => $route ) {
            if ( !\in_array( $method, $route->http_methods ) ) {
                continue;
            }

            $regexes[] = '(?>' . $route->regex . '(*:' . $name . '))';
        }

        $this->compiled[$method] = '~^(?|' . \implode( '|', $regexes ) . ')$~ixX';

        return;
    }
}
