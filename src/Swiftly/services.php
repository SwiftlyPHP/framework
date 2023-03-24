<?php

use Swiftly\Factory\DatabaseAdapterFactory;
use Swiftly\Factory\DatabaseWrapperFactory;
use Swiftly\Factory\TemplateFinderFactory;
use Swiftly\Factory\MatcherFactory;

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
    Swiftly\Http\Server\Request::class => ['handler' => [Swiftly\Http\Server\RequestFactory::class, 'fromGlobals']],

    // Database
    Swiftly\Database\AdapterInterface::class => ['handler' => [DatabaseAdapterFactory::class, 'create']],
    Swiftly\Database\Wrapper::class => ['handler' => [DatabaseWrapperFactory::class, 'create']],

    // Template engine
    Swiftly\Template\TemplateInterface::class => Swiftly\Template\Engine::class,
    Swiftly\Template\ContextInterface::class => Swiftly\Template\Context\HelperContext::class,
    Swiftly\Template\FileFinder::class => ['handler' => [TemplateFinderFactory::class, 'create']],

    // Route parser
    Swiftly\Routing\FileLoaderInterface::class => Swiftly\Routing\File\JsonFile::class,
    Swiftly\Routing\ProviderInterface::class => Swiftly\Routing\Provider\FileProvider::class,
    Swiftly\Routing\ParserInterface::class => Swiftly\Routing\Parser\DefaultParser::class,
    Swiftly\Routing\UrlGenerator::class => Swiftly\Routing\UrlGenerator::class,
    Swiftly\Routing\Matcher\StaticMatcher::class => Swiftly\Routing\Matcher\StaticMatcher::class,
    Swiftly\Routing\Matcher\RegexMatcher::class => Swiftly\Routing\Matcher\RegexMatcher::class,
    Swiftly\Routing\MatcherInterface::class => ['handler' => [MatcherFactory::class, 'create']],
    Swiftly\Routing\Collection::class => ['handler' => [Swiftly\Routing\Provider\FileProvider::class, 'provide']]
];
