<?php

/**
 * Provides some of the default services used by most web apps
 *
 * @author clvarley
 */

return [

    // Startup middleware
    Swiftly\Middleware\CacheReaderMiddleware::class => Swiftly\Middleware\CacheReaderMiddleware::class,
    Swiftly\Middleware\ControllerMiddleware::class => Swiftly\Middleware\ControllerMiddleware::class,
    Swiftly\Middleware\RoutingMiddleware::class => Swiftly\Middleware\RoutingMiddleware::class,

    // HTTP services
    Swiftly\Http\Server\RequestFactory::class => Swiftly\Http\Server\RequestFactory::class,
    Swiftly\Http\Server\Request::class => [
        'handler' => [ Swiftly\Http\Server\RequestFactory::class, "fromGlobals" ]
    ],
    Swiftly\Http\Server\Response::class => Swiftly\Http\Server\Response::class,

    // Database
    Swiftly\Database\Database::class => [
        'handler'   => function ( Swiftly\Dependency\AdapterInterface $db ) {
            $database = new Swiftly\Database\Database( $db );
            $database->open();
            return $database;
        }
    ],

    // Template engine
    Swiftly\Template\TemplateInterface::class => Swiftly\Template\Php::class,

    // Route parser
    Swiftly\Routing\ParserInterface::class => Swiftly\Routing\Parser\JsonParser::class,
    Swiftly\Routing\Dispatcher::class => Swiftly\Routing\Dispatcher::class
];
