<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Routing\MatcherInterface;
use Swiftly\Dependency\Container;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Http\Status;
use Swiftly\Routing\MatchedRoute;

use function is_array;
use function strpos;

/**
 * Middleware responsible for matching routes to controllers
 *
 * @author clvarley
 */
Class RoutingMiddleware Implements MiddlewareInterface
{

    /**
     * Route matching component
     *
     * @var MatcherInterface $router Route matcher
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
     * @param MatcherInterface $router Route matcher
     * @param Container $container     Dependency container
     */
    public function __construct(
        MatcherInterface $router,
        Container $container
    ) {
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
        $current = $this->router->match( $path );

        // No matches, 404 and exit early!
        if ( $current === null ) {
            return new Response(
                '404 - No route found',
                STATUS::NOT_FOUND
            );
        }

        // Route found, but unsupported verb
        if ( !$current->route->supports($method) ) {
            return new Response(
                '405 - HTTP method not allowed',
                STATUS::METHOD_NOT_ALLOWED
            );
        }

        // Class should be in App\Controller namespace
        $handler = $current->route->getHandler();

        if (is_array($handler)
            && strpos($handler[0], 'App\\Controller\\') === false
        ) {
            $handler[0] = 'App\\Controller\\' . $handler[0];
        }

        // Expose current route if anyone needs it
        $this->container->bind(
            MatchedRoute::class,
            $current
        );

        // Expose controller to later middleware
        $this->container->bind(
            Response::class,
            $handler
        )->parameters(
            $current->args
        );

        return $next( $request, $response );
    }
}
