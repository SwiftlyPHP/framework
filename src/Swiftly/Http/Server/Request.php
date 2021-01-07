<?php

namespace Swiftly\Http\Server;

use \Swiftly\Http\{
    Headers,
    Parameters,
    Url
};

/**
 * Class used to represent HTTP requests coming into the server
 *
 * @author clvarley
 */
Class Request
{

    /**
     * Recognised HTTP methods we can respond to
     *
     * We are not currently planning to support the TRACE or CONNECT verbs.
     *
     * @var string[] ALLOWED_METHODS HTTP verbs
     */
    public const ALLOWED_METHODS = [
        'OPTIONS',
        'HEAD',
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    /**
     * Request HTTP headers
     *
     * @var Headers $headers HTTP headers
     */
    public $headers;

    /**
     * HTTP query string parameters
     *
     * @var Parameters $query Query parameters
     */
    public $query;

    /**
     * HTTP POST parameters
     *
     * @var Parameters $post POST parameters
     */
    public $post;

    /**
     * HTTP method used
     *
     * @var string $method HTTP verb
     */
    protected $method;

    /**
     * URL used to generate this request
     *
     * @var Url $url Requested URL
     */
    protected $url;

    /**
     * Request payload
     *
     * @var mixed $content Request body
     */
    protected $content;

    /**
     * Creates a new Request object from the provided values
     *
     * We do not recommend creating this object directly, instead please use the
     * RequestFactory class to instantiate new instances of this class.
     *
     * @internal
     * @param string $method    HTTP verb
     * @param Url $url          Request URL
     * @param Headers $headers  HTTP headers
     * @param Parameters $query Query parameters
     * @param Parameters $post  Post parameters
     */
    public function __construct( string $method, Url $url, Headers $headers, Parameters $query, Parameters $post )
    {
        $this->method  = \in_array( $method, static::ALLOWED_METHODS ) ? $method : 'GET';
        $this->url     = $url;
        $this->headers = $headers;
        $this->query   = $query;
        $this->post    = $post;
    }

    /**
     * Returns the HTTP method used for this request
     *
     * @return string HTTP verb
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Returns the protocol of this request
     *
     * @return string Request protocol
     */
    public function getProtocol() : string
    {
        return $this->url->scheme;
    }

    /**
     * Returns the URL path of this request
     *
     * @return string Request path
     */
    public function getPath() : string
    {
        return $this->url->path;
    }

    /**
     * Checks if this request was made via a secure protocol
     */
    public function isSecure() : bool
    {
        return $this->url->scheme === 'https';
    }
}
