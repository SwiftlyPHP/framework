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
        'handler' => [Swiftly\Http\Server\RequestFactory::class, 'fromGlobals']
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
            return new Swiftly\Template\FileFinder(APP_VIEW . '/');
        }
    ],

    // Route parser
    Swiftly\Routing\FileLoaderInterface::class => Swiftly\Routing\File\JsonFile::class,
    Swiftly\Routing\ProviderInterface::class => Swiftly\Routing\Provider\FileProvider::class,
    Swiftly\Routing\ParserInterface::class => Swiftly\Routing\Parser\DefaultParser::class,
    Swiftly\Routing\UrlGenerator::class => Swiftly\Routing\UrlGenerator::class,
    Swiftly\Routing\Matcher\StaticMatcher::class => Swiftly\Routing\Matcher\StaticMatcher::class,
    Swiftly\Routing\Matcher\RegexMatcher::class => Swiftly\Routing\Matcher\RegexMatcher::class,
    Swiftly\Routing\MatcherInterface::class => [
        'handler' => function (
            Swiftly\Routing\Matcher\StaticMatcher $static,
            Swiftly\Routing\Matcher\RegexMatcher $dynamic
        ) {
            return new Swiftly\Routing\Matcher\SeriesMatcher([$static, $dynamic]);
        }
    ],
    Swiftly\Routing\Collection::class => [
        'handler' => [Swiftly\Routing\Provider\FileProvider::class, 'provide']
    ]
];
