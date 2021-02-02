<?php

namespace Swiftly\Application;

use Swiftly\Config\Store;
use Swiftly\Routing\{
    Dispatcher,
    Route
};
use Swiftly\Dependency\{
    Container,
    Service,
    Loader\PhpLoader
};
use Swiftly\Http\Server\{
    Request,
    Response
};
use Swiftly\Database\{
    Database,
    AdapterInterface,
    Adapters\Mysql,
    Adapters\Postgres,
    Adapters\Sqlite
};
use Swiftly\Middleware\{
    CacheReaderMiddleware,
    ControllerMiddleware,
    RoutingMiddleware,
    Runner
};

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
        if ( \is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

        // Run startup middleware
        $startup = $this->getStartup();
        $response = $startup->run( $request, $response );

        // Run shutdown middleware
        $shutdown = $this->getShutdown();
        $response = $shutdown->run( $request, $response );

        // No choice but to 404
        if ( empty( $response ) || !$response instanceof Response ) {
            $response = new Response( '', 404 );
        }

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
        switch( \mb_strtolower( $config['adapter'] ) ) {
            case 'sqlite':
                $adapter = Sqlite::class;
                break;

            case 'postgres':
            case 'postgresql':
                $adapter = Postgres::class;
                break;

            case 'mysql':
            case 'mysqli':
            default:
                $adapter = Mysql::class;
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
            // TODO:
        ]);
    }
}
