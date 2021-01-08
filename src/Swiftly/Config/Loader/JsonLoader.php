<?php

namespace Swiftly\Config\Loader;

use Swiftly\Config\{
    LoaderInterface,
    Store
};

use function is_string;
use function is_array;
use function is_readable;
use function file_get_contents;
use function json_decode;
use function json_last_error;

use const JSON_ERROR_NONE;

/**
 * Class for loading and parsing json config files
 *
 * @author clvarley
 */
Class JsonLoader Implements LoaderInterface
{

    /**
     * Path to the config JSON file
     *
     * @var string $filename File path
     */
    protected $filename;

    /**
     * Name of the current group being parsed
     *
     * Used to track the group name during recursive calls to the
     * {@see JsonLoader::parse} method.
     *
     * @internal
     * @var string $groupname Group name
     */
    private $groupname = '';

    /**
     * Creates a loader for the given config file
     *
     * @param string $filename File path
     */
    public function __construct( string $filename )
    {
        $this->filename = $filename;
    }

    /**
     * Loads values from the JSON file into the given store
     *
     * @param Store $config Config store
     * @return Store        Updated store
     */
    public function load( Store $config ) : Store
    {
        $json = $this->json();

        // Nothing to do!
        if ( empty( $json ) ) {
            return $config;
        }

        $this->parse( $json, $config );

        return $config;
    }

    /**
     * Recursively parse the values into the store
     *
     * @param array $values Config values
     * @param Store $config Config store
     * @return void         N/a
     */
    private function parse( array $values, Store $config ) : void
    {
        $previous = $this->groupname;

        foreach ( $values as $name => $value ) {
            // Already inside a group?
            if ( !empty( $previous ) ) {
                $name = "$previous.$name";
            }

            $this->groupname = $name;

            // Recurse if required
            if ( is_array( $value ) ) {
                $this->parse( $value, $config );
            }

            $config->set( $this->groupname, $value );
        }

        // Reset
        $this->groupname = $previous;

        return;
    }

    /**
     * Attempt to load the JSON file
     *
     * @return array JSON data
     */
    private function json() : array
    {
        if ( !is_readable( $this->filename ) ) {
            return [];
        }

        $content = (string)file_get_contents( $this->filename );
        $content = json_decode( $content, true );

        // Parse error?
        if ( !is_array( $content ) || json_last_error() !== JSON_ERROR_NONE ) {
            return [];
        }

        return $content;
    }
}
