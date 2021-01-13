<?php

namespace Swiftly\Application;

use Swiftly\Config\Store;
use Swiftly\Template\TemplateInterface;
use Swiftly\Routing\Dispatcher;
use Swiftly\Dependency\{
    Container,
    Dependency,
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

        // TODO:
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

        // Load route.json and dispatch
        if ( \is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

        // Match URL to defined route
        $route = $router->dispatch(
            $request->getMethod(),
            $request->getPath()
        );

        if ( $route !== null ) {
            $controller = new Dependency( $route->callable, $this->dependencies );
            $controller->arguments( $route->args );

            // Get the Response object
            $response = $controller->resolve();
        }

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
}
