<?php

namespace Swiftly\Routing;

/**
 * P.O.D used to represent a single route
 *
 * @author clvarley
 */
Class Route
{

    /**
     * Regex used to match against this route
     *
     * @var string $regex Route regex
     */
    public $regex = '';

    /**
     * Allowed HTTP methods for this route
     *
     * @var array $http_methods Allowed methods
     */
    public $http_methods = [];

    /**
     * Class for this route
     *
     * @var string $class Class name
     */
    public $class = '';

    /**
     * Class method to be called
     *
     * @var string $method Method name
     */
    public $method = '';

    /**
     * Optional route arguments
     *
     * @var array $arguments Route args
     */
    public $arguments = [];

}
