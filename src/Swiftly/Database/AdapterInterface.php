<?php

namespace Swiftly\Database;

/**
 * Interface that all DB adapters/drivers should implement
 *
 * @author clvarley
 */
Interface AdapterInterface
{

    /**
     * Create a new adapter using the passed credentials
     *
     * @param array $options Database options
     */
    public function __construct( array $options );

    /**
     * Open a connection to the database
     *
     * @return bool Connection made successfully?
     */
    public function open() : bool;

    /**
     * Execute the given query
     *
     * @param string $query Query
     * @return bool         Query produced result
     */
    public function query( string $query ) : bool;

    /**
     * Get a single row from the last query
     *
     * @return array Query result
     */
    public function getResult() : array;

    /**
     * Get the results from the last query
     *
     * @return array[] Query results
     */
    public function getResults() : array;

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int;

    /**
     * Close the connection to the database
     */
    public function close() : void;

}
