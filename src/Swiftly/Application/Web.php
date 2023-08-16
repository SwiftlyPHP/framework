<?php

namespace Swiftly\Application;

use Swiftly\Config\Store;
use Swiftly\Routing\File\JsonFile;
use Swiftly\Routing\FileLoaderInterface;
use Swiftly\Dependency\Container;
use Swiftly\Dependency\Loader\PhpLoader;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Middleware\CacheReaderMiddleware;
use Swiftly\Middleware\CacheWriterMiddleware;
use Swiftly\Middleware\ControllerMiddleware;
use Swiftly\Middleware\RoutingMiddleware;
use Swiftly\Middleware\Runner;

use const APP_SWIFTLY;
use const APP_CONFIG;

/**
 * The front controller for our web app
 *
 * @author clvarley
 */
Class Web
{

    /**
     * @var Store $config Configuration values
     */
    private $config;

    /**
     * @var Container $services Dependency manager
     */
    private $dependencies;

    /**
     * Create our application
     *
     * @param Store $config Configuration values
     */
    public function __construct( Store $config )
    {
        $this->config = $config;

        $services = new PhpLoader( APP_SWIFTLY . '/services.php'  );

        // Register default services
        $this->dependencies = new Container;
        $this->dependencies->load( $services );

        // Bind this config
        $this->dependencies->bind( Store::class, $config );
        $this->dependencies->bind( Container::class, $this->dependencies );
    }

    /**
     * Start our app
     */
    public function start() : void
    {
        // Defer loading of route definitions
        $this->dependencies->bind(FileLoaderInterface::class, JsonFile::class)
            ->parameters(['file_path' => APP_CONFIG . '/routes.json']);

        // Get the global request object
        $request = $this->dependencies->resolve(Request::class);
        $response = new Response;

        // Run startup middleware
        $startup = $this->getStartup();
        $response = $startup->run( $request, $response );

        // Run shutdown middleware
        $shutdown = $this->getShutdown();
        $response = $shutdown->run( $request, $response );

        // Send the response and end!
        $response->send();

        return;
    }

    /**
     * Gets the middleware to be run at startup
     *
     * @return Runner Startup middleware runner
     */
    private function getStartup() : Runner
    {
        return new Runner([
            $this->dependencies->resolve( CacheReaderMiddleware::class ),
            $this->dependencies->resolve( RoutingMiddleware::class ),
            $this->dependencies->resolve( ControllerMiddleware::class )
        ]);
    }

    /**
     * Gets the middleware to be run at shutdown
     *
     * @return Runner Shutdown middleware runner
     */
    private function getShutdown() : Runner
    {
        return new Runner([
            $this->dependencies->resolve( CacheWriterMiddleware::class )
        ]);
    }
}
