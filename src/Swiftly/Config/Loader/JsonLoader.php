<?php

namespace Swiftly\Config\Loader;

use Swiftly\Config\{
    Config,
    LoaderInterface
};

/**
 * Loads config values from JSON files
 *
 * @author clvarley
 */
Class JsonLoader Implements LoaderInterface
{

    /**
     * Path to the JSON config file
     *
     * @var string $filepath File path
     */
    private $filepath;

    /**
     * Prepares the given file for loading
     *
     * @param string $filepath File path
     */
    public function __construct( string $filepath )
    {
        $this->filepath = $filepath;
    }

    /**
     * Loads the JSON into the config object
     *
     * @param Config $config Config object
     * @return Config        Updated config
     */
    public function load( Config $config ) : Config
    {
        $json = $this->getJson();

        if ( empty( $json ) ) {
            return $config;
        }

        $this->parse( '', $json, $config );

        return $config;
    }

    /**
     * Parse our custom config structure and strip out values
     *
     * @param string $name   Setting name
     * @param mixed $data    Setting value
     * @param Config $config Config object
     * @return void          N/a
     */
    public function parse( string $name, /* mixed */ $data, Config $config ) : void
    {
        if ( !\is_array( $data ) ) {
            $data = [ $data ];
        }

        foreach ( $data as $index => $value ) {
            $index = \is_string( $index ) ? $index : '';

            if ( !empty( $name ) ) {
              $index = "$name.$index";
            }

            // Recurse if array
            if ( \is_array( $value ) ) {
                $this->parse( $index, $value, $config );
            }

            $config->set( $index, $value );
        }

        return;
    }

    /**
     * Attempts to load the JSON file
     *
     * @return array JSON data
     */
    private function getJson() : array
    {
        if ( !\is_readable( $this->filepath ) ) {
            return [];
        }


        $contents = (string)\file_get_contents( $this->filepath );
        $contents = \json_decode( $contents, true );

        if ( empty( $contents ) || \json_last_error() !== \JSON_ERROR_NONE ) {
            return [];
        }

        return $contents;
    }
}
