<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Dependency\Container;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Base\AbstractController;

/**
 * Middleware responsible for calling the controller
 *
 * @author clvarley
 */
Class ControllerMiddleware Implements MiddlewareInterface
{

    /**
     * Reference to the main dependency container
     *
     * @var Container $container Dependency container
     */
    private $container;

    /**
     * Create a middleware for executing the controller
     *
     * @param Container $container Dependency container
     */
    public function __construct( Container $container )
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        $result = $this->container->resolve( AbstractController::class );

        // Route matched but no response?
        if ( empty( $result ) || !$result instanceof Response ) {
            $result = new Response( '', 500 );
        }

        // Return the controller response!
        return $result;
    }
}
