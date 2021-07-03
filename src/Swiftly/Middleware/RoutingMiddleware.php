<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Routing\Dispatcher;
use Swiftly\Dependency\Container;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Base\AbstractController;

/**
 * Middleware responsible for matching routes to controllers
 *
 * @author clvarley
 */
Class RoutingMiddleware Implements MiddlewareInterface
{

    /**
     * The regex router
     *
     * @var Dispatcher $router Regex router
     */
    private $router;

    /**
     * Reference to the main dependency container
     *
     * @var Container $container Dependency container
     */
    private $container;

    /**
     * Create a middleware to manage routing
     *
     * @param Dispatcher $router   Regex router
     * @param Container $container Dependency container
     */
    public function __construct( Dispatcher $router, Container $container )
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Get the route
        $route = $this->router->dispatch( $method, $path );

        // No matches, 404 and exit early!
        if ( empty( $route ) ) {
            return new Response( '', 404 );
        }

        // Expose controller to later middleware
        $this->container->bind(
            AbstractController::class,
            $route->callable
        )->parameters(
            $route->args
        );

        return $next( $request, $response );
    }
}
