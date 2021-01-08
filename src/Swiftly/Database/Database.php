<?php

namespace Swiftly\Database;

use Swiftly\Database\AdapterInterface;

/**
 * A wrapper around the underlying database implementations
 *
 * @author clvarley
 */
Class Database
{

    /**
     * Database specific implementation
     *
     * @var AdapterInterface $adapter Database adapter
     */
    private $adapter;

    /**
     * Database connection status
     *
     * @var bool $connected Connection status
     */
    private $connected = false;

    /**
     * Create a wrapper around the given adapter
     *
     * @param AdapterInterface $adapter The database adapter to use
     */
    public function __construct( AdapterInterface $adapter )
    {
        $this->adapter = $adapter;
    }

    /**
     * Makes sure we properly close the connection to the database
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Attempts to open a connection to the database
     *
     * @return bool Database connected?
     */
    public function open() : bool
    {
        if ( !$this->connected ) {
            $this->connected = $this->adapter->open();
        }

        return $this->connected;
    }

    /**
     * Close the connection to the database
     *
     * @return void N/a
     */
    public function close() : void
    {
        if ( $this->connected ) {
            $this->adapter->close();
            $this->connected = false;
        }

        return;
    }

    /**
     * Executes the given query
     *
     * @param string $query SQL query
     */
    public function query( string $query ) : void
    {
        if ( $this->connected ) {
            $this->adapter->query( $query );
        }

        return;
    }

    /**
     * Executes the given query and returns the first result
     *
     * @param string $query SQL query
     * @return array        Query result
     */
    public function queryResult( string $query ) : array
    {
        if ( !$this->connected || !$this->adapter->query( $query ) ) {
            return [];
        }

        return $this->adapter->getResult();
    }

    /**
     * Executes the given query and returns all the results
     *
     * @param string $query SQL query
     * @return array        Query results
     */
    public function queryResults( string $query ) : array
    {
        if ( !$this->connected || !$this->adapter->query( $query ) ) {
            return [];
        }

        return $this->adapter->getResults();
    }

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return ( $this->connected
            ? $this->adapter->getLastId()
            : 0
        );
    }

    /**
     * Get the connection status
     *
     * @return bool Connection open?
     */
    public function isConnected() : bool
    {
        return $this->connected;
    }
}
