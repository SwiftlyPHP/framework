<?php

/**
 * Provides simple wrapper around autoload
 *
 * @author clvarley
 */
Final Class Autoloader
{

    /**
     * @var string[] $prefixes Namespace mappings
     */
    private $prefixes = [];

    /**
     * Registers this autoloader with spl
     */
    public function __construct()
    {
        spl_autoload_register([ $this, 'find' ]);
    }

    /**
     * Adds a new namespace - folder mapping to the autoloader
     *
     * @param string $prefix  Namespace prefix
     * @param string $path    Folder path
     */
    public function addPrefix( string $prefix, string $path ) : void
    {
        if ( is_dir( $path ) ) {
            $this->prefixes[strtolower( $prefix )] = rtrim( $path, '/' ) . '/';
        }
    }

    /**
     * Find the file for a class
     *
     * Used by spl_autoload_register
     *
     * @param string $class_name  Class name
     * @return void               N/a
     */
    public function find( string $class_name ) : void
    {

        $parts = explode( '\\', $class_name );
        $class = array_pop( $parts );
        $vendor = array_shift( $parts );

        // Get class vendor
        if ( !empty( $vendor ) ) {
            $vendor = strtolower( $vendor );
        } else {
            $vendor = '*';
        }

        // Vendor directory override?
        if ( !empty( $this->prefixes[$vendor] ) ) {
            $dir_path = $this->prefixes[ $vendor ];
        } else {
            $dir_path = get_include_path() . '/';
        }

        // Attempt 1. Simple file include?
        if ( is_file( $file = $dir_path . implode( '/', $parts ) . "/$class.php" ) ) {
            require $file;
            return;
        }

        // Atempt 2. Traverse directories
        while ( !empty( $parts ) ) {
            $directory = scandir( $dir_path, SCANDIR_SORT_NONE );

            $current = array_shift( $parts );

            foreach ( $directory as $dir ) {
                if ( strcasecmp( $dir, $current ) === 0 ) {
                    $dir_path .= "$dir/";
                    continue 2;
                }
            }

            return;
        }

        $files = scandir( $dir_path, SCANDIR_SORT_NONE );
        $class = strtolower( $class );

        foreach ( $files as $file ) {
            if ( strtolower( substr( $file, 0, -4 ) ) === $class ) {
                require $dir_path . $file;
                return;
            }
        }

        return; // Not found!
    }
}
