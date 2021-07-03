<?php

namespace Swiftly\Middleware;

use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;

/**
 * Interface for middleware components
 *
 * @author clvarley
 */
Interface MiddlewareInterface
{

    /**
     * Execute this middleware
     *
     * @psalm-param callable(Request,Response):Response $next
     *
     * @param Request $request   HTTP request
     * @param Response $response HTTP response
     * @param callable $next     Next middleware
     * @return Response          Filtered HTTP response
     */
    public function run( Request $request, Response $response, callable $next ) : Response;

}
