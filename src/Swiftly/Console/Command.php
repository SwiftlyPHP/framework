<?php

namespace Swiftly\Console;

/**
 * Provides utility methods for dealing with console arguments
 *
 * @author clvarley
 */
Class Command
{

    /**
     * The name of this command
     *
     * @var string $name Command name
     */
    private $name = '';

    /**
     * The options passed to this command
     *
     * @var array $options Options array
     */
    private $options = [];

    /**
     * The arguments passed to this command
     *
     * @var array $arguments Array of arguments
     */
    private $arguments = [];

    /**
     * The working directory of this command
     *
     * @var array $directory Working directory
     */
    private $directory = '';

    /**
     * Create a new arguments object
     *
     * @param string $name      Command name
     * @param array $arguments  Command arguments
     * @param array $options    Command options
     */
    public function __construct( string $name = '', array $arguments = [], array $options = [] )
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->options = $options;
    }

    /**
     * Creates a new command object from PHP/CLI variables
     *
     * @static
     *
     * @return Command Command object
     */
    public static function fromGlobals() : Command
    {
        // Set the global CLI arguments
        global $argv;

        $name = '';
        $args = [];

        // Attempt to get the command name
        if ( isset( $argv ) && isset( $argv[0] ) ) {

            // First arg will sometimes be the Swiftly core file
            if ( \is_file( $argv[0] ) && \mb_substr( $argv[0], -16 ) === 'public/index.php' ) {

                $name = ( isset( $argv[1] ) ? $argv[1] : '' );

            } else {

                $name = $argv[0];

            }

            $args = \array_slice( $args, 1 );

        }

        $command = new Command();

        // Set the values
        $command->setName( $name );
        $command->setArguments( $args );
        $command->setDirectory( \getcwd() );

        return $command;
    }

    /**
     * Returns the name of this command
     *
     * @return string Command name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Sets the name of this command
     *
     * @param string $name Command name
     * @return void         N/a
     */
    protected function setName( string $name ) : void
    {
        $this->name = $name;
    }

    /**
     * Gets the value of a option passed to the command
     *
     * @param string $option  Option name
     * @return string         N/a
     */
    public function getOption( string $option ) : string
    {
        return ( isset( $this->options[$option] ) ? $this->options[$option] : '' );
    }

    /**
     * Gets all the options passed to the command
     *
     * @return array Options
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Sets the value of an option for this command
     *
     * @param string $name  Option name
     * @param string $value Option value
     * @return void         N/a
     */
    protected function setOption( string $name, string $value ) : void
    {
        $this->options[$name] = $value;
    }

    /**
     * Sets all the options of this command
     *
     * @param array $options  Options
     * @return void           N/a
     */
    protected function setOptions( array $options ) : void
    {
        $this->options = $options;
    }

    /**
     * Checks to see if an option is set
     *
     * @param string $name  Option name
     * @return bool         Exists?
     */
    public function hasOption( string $name ) : bool
    {
        return ( \array_key_exists( $name, $this->options ) );
    }

    /**
     * Checks to see if the given flag is set
     *
     * Alias for the hasOption method
     *
     * @uses Command::hasOption
     * @param string $name  Flag name
     * @return bool         Exists
     */
    public function hasFlag( string $name ) : bool
    {
        return $this->hasOption( $name );
    }

    /**
     * Gets the first argument of the command
     *
     * @return string First argument
     */
    public function getArgument() : string
    {
        return ( isset( $this->arguments[0] ) ? $this->arguments[0] : '' );
    }

    /**
     * Gets all the arguments passed to the command
     *
     * @return array Arguments
     */
    public function getArguments() : array
    {
        return $this->arguments;
    }

    /**
     * Adds an argument to this command
     *
     * @param string $argument  Argument value
     * @return void             N/a
     */
    protected function addArgument( string $argument ) : void
    {
        $this->arguments[] = $argument;
    }

    /**
     * Sets all the arguments of this command
     *
     * @param array $arguments  Arguments
     * @return void             N/a
     */
    protected function setArguments( array $arguments ) : void
    {
        $this->arguments = $arguments;
    }

    /**
     * Gets the current working directory of this command
     *
     * @return string Working directory
     */
    public function getDirectory() : string
    {
        return $this->directory;
    }

    /**
     * Sets the current working directory of this command
     *
     * @param string $directory Working directory
     */
    protected function setDirectory( string $directory ) : void
    {
        $this->directory = ( \is_dir( $directory ) ? $directory : $this->directory );
    }

}
