<?php

namespace Swiftly\Base;

use Swiftly\Database\Wrapper;

/**
 * The abstract class all models should inherit
 *
 * @author clvarley
 */
Abstract Class AbstractModel
{

    /**
     * @var Wrapper $database DB wrapper
     */
    protected $database;

    /**
     * Pass the db object into the model
     *
     * @param Wrapper $database DB wrapper
     */
    public function __construct( Wrapper $database )
    {
        $this->database = $database;
    }
}
