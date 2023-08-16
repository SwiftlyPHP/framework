<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Dependency\Container;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Dependency\Exception\UnexpectedTypeException;

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
        // Call the user controller and try to get a response
        try {
            $result = $this->container->resolve( Response::class );
            
        // Route matched but not a valid response?
        } catch ( UnexpectedTypeException $e ) {
            $result = new Response( '505 - Controller did not return a response', 500 );

        // Controller threw an expection
        } catch ( \Exception $e ) {
            $result = new Response(
                "<h2>Unhandled " . get_class($e) . "</h2>\n"
                . "<p>{$e->getMessage()}</p>"
                . "<pre>{$e->getTraceAsString()}</pre>",
                500
            );
        }

        // Return the controller response!
        return $result;
    }
}
