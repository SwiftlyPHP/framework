<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Console\{ Input, Output, Command };
use \Swiftly\Routing\Router;

/**
 * The front controller for our console app
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Console Implements ApplicationInterface
{

    /**
     * @var Config $config Configuration values
     */
    private $config = null;

    /**
     * @var Manager $services Service manager
     */
    private $services = null;

    /**
     * Create our application
     *
     * @param Config $config Configuration values
     */
    public function __construct( Config $config )
    {
        $this->config = $config;

        $this->services = Manager::getInstance();
        $this->services->registerService( 'output', new Output() );
        $this->services->registerService( 'input', new Input() );
        $this->services->registerService( 'command', Command::fromGlobals() );
    }

    /**
     * Start our app
     */
    public function start() : void
    {

        // Get the router
        if ( is_file( APP_CONFIG . 'commands.json' ) ) {
            $router = Router::fromJson( APP_CONFIG . 'commands.json' );
        } else {
            $router = new Router();
        }

        $incoming = $this->services->getService( 'command' );

        $command_name = $incoming->getName();

        $action = $router->get( $command_name );

        // Did we return a callable action?
        if ( is_null( $action ) ) {

            $cli = $this->services->getService( 'output' );

            // Alert the user!
            $cli->toRed()
                ->write( 'Swiftly Error: ' )
                ->reset()
                ->writeLine( sprintf(
                    'Could not find a handler for command \'%s\'',
                    $command_name
                ));

            // Quit with error!
            exit( 1 );

        } else {

            list( $controller, $method ) = $action;

            $controller = new $controller( $this->services );

            // Make sure quiting stops the program
            ignore_user_abort( false );

            // Call the method
            $controller->{$method}();

        }

        // NOTE: Controllers should exit with their own status code!
        return;
    }

}
