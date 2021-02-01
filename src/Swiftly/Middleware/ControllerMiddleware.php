<?php

namespace Swiftly\Middleware;

use Swiftly\Http\Server\{
  Request,
  Response
};
use Swiftly\Dependency\Service;
use Swiftly\Middleware\MiddlewareInterface;

/**
 * Middleware responsible for calling the controller
 *
 * @author clvarley
 */
Class ControllerMiddleware Implements MiddlewareInterface
{

    /**
     * The controller for this route
     *
     * @var Service $controller Route controller
     */
    private $controller;

    /**
     * Create a middleware for the route controller
     *
     * @param Service $controller Route controller
     */
    public function __construct( Service $controller )
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        $result = $this->controller->resolve();

        // Route matched but no response?
        if ( empty( $result ) || !$result instanceof Response ) {
            $response = new Response( '', 500 );
        }

        // Return the controller response!
        return $result;
    }
}
