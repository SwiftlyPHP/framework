<?php

namespace Swiftly\Database\Adapters;

use \Swiftly\Database\AdapterInterface;

/**
 * Driver for MySQL databases
 *
 * @author clvarley
 */
Class Mysql Implements AdapterInterface
{

    /**
     * Handle to the MySQL DB
     *
     * @var \mysqli $handle DB handle
     */
    private $handle = null;

    /**
     * Results of last query
     *
     * @var \mysqli_result $results Query results
     */
    private $results = null;

    /**
     * Database username
     *
     * @var string $username DB username
     */
    private $username;

    /**
     * Database password
     *
     * @var string $password DB password
     */
    private $password;

    /**
     * Database name
     *
     * @var string $name DB name
     */
    private $name;

    /**
     * Database host
     *
     * @var string $host DB host
     */
    private $host = '127.0.0.1';

    /**
     * Database port
     *
     * @var int $port DB port
     */
    private $port = 3306;

    /**
     * Get the credentials from the options array
     *
     * @param array $options Database options
     */
    public function __construct( array $options )
    {
        $this->username = ( isset( $options['username'] ) ? $options['username'] : '' );
        $this->password = ( isset( $options['password'] ) ? $options['password'] : '' );
        $this->name = ( isset( $options['name'] ) ? $options['name'] : '' );
        $this->host = ( isset( $options['host'] ) ? $options['host'] : $this->host );
        $this->port = ( isset( $options['port'] ) ? $options['post'] : $this->port );
    }

    /**
     * Opens a connection to the MySQL database
     *
     * @return bool Connection opened
     */
    public function open() : bool
    {
        $this->handle = new \mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->name,
            $this->port
        );

        $status = true;

        // Check for connection error
        return ( $this->handle->connect_errno === 0 );
    }

    /**
     * Executes the given query
     *
     * @param string $query   SQL query
     * @return bool           Produced result?
     */
    public function query( string $query ) : bool
    {
        $result = $this->handle->query( $query );

        if ( $result === false ) {
            return false;
        }

        // Free memory
        if ( !\is_null( $this->results ) ) {
            $this->results->free();
        }

        // Store results object
        if ( \is_object( $result ) ) {
            $this->results = $result;
        } else {
            $this->results = null;
        }

        return true;
    }

    /**
     * Returns a single result from the last query
     *
     * @return array Query result
     */
    public function getResult() : array
    {
        return ( \is_null( $this->results )
            ? []
            : $this->results->fetch_array( \MYSQLI_ASSOC )
        );
    }

    /**
     * Returns all results from the last query
     *
     * @return array Query results
     */
    public function getResults() : array
    {
        return ( \is_null( $this->results )
            ? []
            : $this->results->fetch_all( \MYSQLI_ASSOC )
        );
    }

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return $this->handle->insert_id;
    }

    /**
     * Closes the connection to the MySQL database
     */
    public function close() : void
    {
        // Free any stray result object
        if ( !\is_null( $this->results ) ) {
            $this->results->free();
        }

        $this->handle->close();

        return;
    }
}
