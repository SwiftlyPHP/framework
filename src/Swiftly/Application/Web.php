<?php

namespace Swiftly\Application;

use Swiftly\Config\Store;
use Swiftly\Routing\Dispatcher;
use Swiftly\Routing\Route;
use Swiftly\Dependency\Container;
use Swiftly\Dependency\Service;
use Swiftly\Dependency\Loader\PhpLoader;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Database\Wrapper;
use Swiftly\Database\AdapterInterface;
use Swiftly\Database\Adapter\MysqlAdapter;
use Swiftly\Database\Adapter\PostgresAdapter;
use Swiftly\Database\Adapter\SqliteAdapter;
use Swiftly\Middleware\CacheReaderMiddleware;
use Swiftly\Middleware\CacheWriterMiddleware;
use Swiftly\Middleware\ControllerMiddleware;
use Swiftly\Middleware\RoutingMiddleware;
use Swiftly\Middleware\Runner;

use function is_file;
use function mb_strtolower;

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

        $services = new PhpLoader( APP_SWIFTLY . 'services.php'  );

        // Register default services
        $this->dependencies = new Container;
        $this->dependencies->load( $services );

        // Bind this config
        $this->dependencies->bind( Store::class, $config );
        $this->dependencies->bind( Container::class, $this->dependencies );

        // Register the appropriate database adapter
        if ( $config->has( 'database' ) ) {
            $this->bindDatabase( $this->dependencies, $config->get( 'database' ) );
        }
    }

    /**
     * Start our app
     */
    public function start() : void
    {
        // Create a new router
        $router = $this->dependencies->resolve( Dispatcher::class );

        // Get the global request object
        $request = $this->dependencies->resolve( Request::class );
        $response = new Response;

        // Load route.json and dispatch
        if ( is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

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
     * Binds the database adapter
     *
     * @param Container $services Dependency manager
     * @param array $config       Database config
     * @return void               N/a
     */
    private function bindDatabase( Container $services, array $config ) : void
    {
        // Get the correct adapter
        switch( mb_strtolower( $config['adapter'] ) ) {
            case 'sqlite':
                $adapter = SqliteAdapter::class;
                break;

            case 'postgres':
            case 'postgresql':
                $adapter = PostgresAdapter::class;
                break;

            case 'mysql':
            case 'mysqli':
            default:
                $adapter = MysqlAdapter::class;
                break;
        }

        // Bind the adapter
        $services->bind( AdapterInterface::class, $adapter )->parameters([
            'options' => $config
        ]);

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
