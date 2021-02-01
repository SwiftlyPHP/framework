<?php

namespace Swiftly\Middleware;

use Swiftly\Http\Server\{
    Request,
    Response
};
use Swiftly\Middleware\MiddlewareInterface;

/**
 * Utility class used to run a series of middleware components
 *
 * @author clvarley
 */
Class Runner
{

    /**
     * Middleware components to be run
     *
     * @var MiddlewareInterface[] $middleware Middleware components
     */
    protected $middleware;

    /**
     * Currently running middleware
     *
     * @var int $index Current middleware
     */
    protected $index = 0;

    /**
     * Create a new runner for the given middlewares
     *
     * @param MiddlewareInterface[] $middleware (Optional) Middleware components
     */
    public function __construct( array $middleware = [] )
    {
        $this->middleware = $middleware;
    }

    /**
     * Adds a new middleware to the runner
     *
     * @param MiddlewareInterface $middleware Middleware component
     * @return void                           N/a
     */
    public function addMiddleware( MiddlewareInterface $middleware ) : void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Execute this middleware stack
     *
     * @param Request $request   HTTP request
     * @param Response $response HTTP response
     * @return Response          Filtered HTTP response
     */
    public function run( Request $request, Response $response ) : Response
    {
        if ( empty( $this->middleware ) ) {
            return $response;
        }

        $this->index = 0;

        return $this->middleware[0]->run(
            $request,
            $response,
            [ $this, 'next' ]
        );
    }

    /**
     * Moves to the next middleware runner
     *
     * @param Request $request   HTTP request
     * @param Response $response HTTP response
     * @return Response          Filtered HTTP response
     */
    public function next( Request $request, Response $response ) : Response
    {
        // Reached the end, reset!
        if ( !isset( $this->middleware[++$this->index] ) ) {
            $this->index = 0;
            return $response;
        }

        return $this->middleware[$this->index]->run(
            $request,
            $response,
            [ $this, 'next' ]
        );
    }
}
