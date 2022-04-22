<?php

/**
 * Provides some of the default services used by most web apps
 *
 * @author clvarley
 */

return [

    // Startup middleware
    Swiftly\Middleware\CacheReaderMiddleware::class => Swiftly\Middleware\CacheReaderMiddleware::class,
    Swiftly\Middleware\CacheWriterMiddleware::class => Swiftly\Middleware\CacheWriterMiddleware::class,
    Swiftly\Middleware\ControllerMiddleware::class => Swiftly\Middleware\ControllerMiddleware::class,
    Swiftly\Middleware\RoutingMiddleware::class => Swiftly\Middleware\RoutingMiddleware::class,

    // HTTP services
    Swiftly\Http\Server\RequestFactory::class => Swiftly\Http\Server\RequestFactory::class,
    Swiftly\Http\Server\Request::class => [
        'handler' => [ Swiftly\Http\Server\RequestFactory::class, 'fromGlobals' ]
    ],

    // Database
    Swiftly\Database\Wrapper::class => [
        'handler'   => function ( Swiftly\Database\AdapterInterface $db ) {
            $database = new Swiftly\Database\Wrapper( $db );
            $database->connect();
            return $database;
        }
    ],

    // Template engine
    Swiftly\Template\TemplateInterface::class => Swiftly\Template\Engine::class,
    Swiftly\Template\ContextInterface::class => Swiftly\Template\Context\HelperContext::class,
    Swiftly\Template\FileFinder::class => [
        'handler' => function () {
            return new Swiftly\Template\FileFinder( APP_VIEW . '/' );
        }
    ],

    // Route parser
    Swiftly\Routing\Collection::class => [
        'handler' => function ( Swiftly\Routing\ProviderInterface $provider ) {
            return $provider->populate( new Swiftly\Routing\Collection() );
        }
    ],
    Swiftly\Routing\ProviderInterface::class => Swiftly\Routing\Provider\JsonProvider::class,
    Swiftly\Routing\CompilerInterface::class => Swiftly\Routing\Compiler\StandardCompiler::class,
    Swiftly\Routing\Dispatcher::class => Swiftly\Routing\Dispatcher::class
];
