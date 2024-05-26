<?php
/**
 * This file is used to load your custom services and classes
 *
 * Any classes added to the container here will be available throughout your
 * application.
 *
 * @version 1.0.0
 */

use Swiftly\Dependency\Container;
use Swiftly\Routing\Parser\DefaultParser;
use Swiftly\Routing\MatcherInterface;
use Swiftly\Routing\Matcher\RegexMatcher;
use Swiftly\Routing\Matcher\StaticMatcher;
use Swiftly\Routing\Matcher\SeriesMatcher;
use Swiftly\Routing\File\JsonFile;
use Swiftly\Routing\ProviderInterface;
use Swiftly\Routing\Provider\FileProvider;
use Swiftly\Routing\Collection;
use Swiftly\Routing\UrlGenerator;
use Swiftly\Core\Middleware\RoutingMiddleware;
use Swiftly\Core\Middleware\SessionMiddleware;
use Swiftly\Template\Context\HelperContext;
use Swiftly\Template\Context\DefaultContext;
use Swiftly\Template\FileFinder;
use Swiftly\Template\Engine;
use Swiftly\Http\SessionHandler;
use Swiftly\Http\Session\NativeSession;

use const Swiftly\FILE_ROUTES;
use const Swiftly\PATH_VIEW;

/**
 * Handles registering application-wide services
 *
 * @param Container $container Service container
 */
return static function (Container $container): void {
    /**  -- Routing -- **/
    $container
        ->register(JsonFile::class)
        ->setArguments([
            'file_path' => FILE_ROUTES
        ]);

    $container
        ->register(FileProvider::class);

    $container
        ->register(DefaultParser::class)
        ->setTags(['routing.parser']);
    
    $container
        ->register(StaticMatcher::class)
        ->setTags(['routing.matcher']);

    $container
        ->register(RegexMatcher::class)
        ->setTags(['routing.matcher']);

    $container
        ->register(SeriesMatcher::class, function (Container $container) {
            return new SeriesMatcher(
                $container->tagged('routing.matcher', MatcherInterface::class)
            );
        });

    $container
        ->register(Collection::class, function (ProviderInterface $provider) {
            return $provider->provide();
        });


    /**  -- Middleware -- **/
    $container
        ->register(RoutingMiddleware::class)
        ->setTags(['middleware']);

    $container
        ->register(SessionMiddleware::class)
        ->setTags(['middleware']);


    /** -- Templating -- **/
    $container
        ->register(DefaultContext::class)
        ->setTags(['template.context']);

    $container
        ->register(HelperContext::class)
        ->setTags(['template.context']);

    $container
        ->register(FileFinder::class)
        ->setArguments([
            'file_path' => PATH_VIEW
        ]);

    $container
        ->register(Engine::class);


    /** -- Sessions -- **/
    $container
        ->register(NativeSession::class)
        ->setTags(['session.storage']);

    $container
        ->register(SessionHandler::class)
        ->setTags(['session']);


    /** -- URL Generation -- **/
    $container
        ->register(UrlGenerator::class);
};
