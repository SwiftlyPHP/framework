<?php

namespace Swiftly\Middleware;

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
     * @return
     */
    public function run(  ) : //
    {

    }
}
