<?php

/**
 * Provides some of the default services used by most web apps
 *
 * @author clvarley
 */

return [

    // HTTP services
    Swiftly\Http\Server\RequestFactory::class => [
        'singleton' => true
    ],
    Swiftly\Http\Server\Request::class => [
        'singleton' => true,
        'handler'   => [ Swiftly\Http\Server\RequestFactory::class, "fromGlobals" ]
    ],
    Swiftly\Http\Server\Response::class => [
        'singleton' => true
    ],

    // Database
    // TODO: Bind correct adapter
    Swiftly\Database\Database::class => [
        'singleton' => true,
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
    Swiftly\Routing\Dispatcher::class => [
        'singleton' => true
    ]
];
