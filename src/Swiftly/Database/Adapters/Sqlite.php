<?php

namespace Swiftly\Database\Adapters;

use Swiftly\Database\AdapterInterface;
use SQLite3;
use SQLite3Result;

use const SQLITE3_OPEN_READWRITE;
use const SQLITE3_OPEN_CREATE;
use const SQLITE3_ASSOC;

/**
 * Driver for SQLite databases
 *
 * @author clvarley
 */
Class Sqlite Implements AdapterInterface
{

    /**
     * Handle to the Sqlite DB
     *
     * @var SQLite3|null $handle Sqlite handle
     */
    private $handle = null;

    /**
     * Result of last query
     *
     * @var SQLite3Result|null $result Sqlite result
     */
    private $result = null;

    /**
     * Path to the Sqlite file
     *
     * @var string $file DB file
     */
    private $file;

    /**
     * Get the path from the options array
     *
     * @param array $options Database options
     */
    public function __construct( array $options )
    {
        $this->file = ( isset( $options['path'] ) ? $options['path'] : '' );
    }

    /**
     * Opens the SQLite file
     *
     * @return bool Opened successfully?
     */
    public function open() : bool
    {
        $this->handle = new SQLite3(
            $this->file,
            SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
        );

        // Naive check to see if successfull
        return ( $this->handle->lastErrorCode() === 0 );
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
        if ( $this->result !== null ) {
            $this->result->finalize();
        }

        // Store result
        $this->result = $result;

        return true;
    }

    /**
     * Returns a single row from the result of the last query
     *
     * @return array Query result
     */
    public function getResult() : array
    {
        return ( $this->result !== null
            ? $this->result->fetchArray( SQLITE3_ASSOC )
            : []
        );
    }

    /**
     * Returns all results from the last query
     * @return array [description]
     */
    public function getResults() : array
    {
        $return = [];

        // Are there results to read?
        if ( $this->result === null || $this->result->numColumns() === 0 ) {
            return $return;
        }

        while (( $result = $this->result->fetchArray( SQLITE3_ASSOC ) )) {
            $return[] = $result;
        }

        return $return;
    }

    /**
     * Gets the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return $this->handle->lastInsertRowID();
    }

    /**
     * Closes the handle to the Sqlite file
     */
    public function close() : void
    {
        // Free any stray result objects
        if ( $this->result !== null ) {
            $this->result->finalize();
        }

        $this->handle->close();

        return;
    }
}
