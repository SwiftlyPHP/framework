<?php

namespace Swiftly\Dependency\Loader;

use Swiftly\Dependency\{
    Container,
    LoaderInterface
};

/**
 * Class used to load dependencies from a PHP file
 *
 * @author C Varley <clvarley>
 */
Class PhpLoader Implements LoaderInterface
{

    /**
     * The PHP dependency file
     *
     * @var string $filepath File path
     */
    private $filepath = '';

    /**
     * Pass in the PHP file to be wrapped
     *
     * @param string $filepath File path
     */
    public function __construct( string $filepath )
    {
        $this->filepath = $filepath;
    }

    /**
     * Attempts to load the dependencies from PHP file
     *
     * @param Container $container Dependency container
     * @return void                N/a
     */
    public function load( Container $container ) : void
    {
        if ( !\is_file( $this->filepath ) ) {
            return;
        }

        // Is this actually a PHP file?
        if ( \mb_substr( $this->filepath, -4 ) !== '.php' ) {
            return;
        }

        $dependencies = include_once $this->filepath;

        if ( !\is_array( $dependencies ) || empty( $dependencies ) ) {
            return;
        }

        foreach ( $dependencies as $name => $options ) {
            if ( !\is_string( $name ) || empty( $name ) ) {
                continue;
            }

            // Has a 'handler' function been set? Or an alternate name given?
            if ( !empty( $options['handler'] ) && \is_callable( $options['handler'] ) ) {
                $implementation = $options['handler'];
            } elseif ( !empty( $options ) && \is_string( $options ) ) {
                $implementation = $options;
            } else {
                $implementation = $name;
            }

            // Add them to the container
            $dependency = $container->bind( $name, $implementation );
            $dependency->singleton( !empty( $options['singleton'] ) );
        }

        return;
    }
}
