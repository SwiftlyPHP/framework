<?php

namespace Swiftly\Http\Server;

use \Swiftly\Http\Headers;

/**
 * Class used to send HTTP responses to the client
 *
 * @author clvarley
 */
Class Response
{

    /**
     * Response HTTP headers
     *
     * @var Headers $headers HTTP headers
     */
    public $headers;

    /**
     * Response status code
     *
     * @var int $status Status code
     */
    protected $status;

    /**
     * Response payload
     *
     * @var string $content Response body
     */
    protected $content;

    /**
     * Creates a new HTTP response using the values provided
     *
     * @param string $content (Optional) Response body
     * @param int $status     (Optional) Status code
     * @param array $headers  (Optional) Http headers
     */
    public function __construct( string $content = '', int $status = 200, array $headers = [] )
    {
        $this->status  = $status;
        $this->content = $content;
        $this->headers = new Headers( $headers );
    }

    /**
     * Sets the status code of this response
     *
     * @param int $status Status code
     * @return void       N/a
     */
    public function setStatus( int $status ) : void
    {
        $this->status = $status;
    }

    /**
     * Sets the content of this response
     *
     * @param string $content Response body
     * @return void           N/a
     */
    public function setContent( string $content ) : void
    {
        $this->content = $content;
    }

    /**
     * Sets the content type of this response
     *
     * @param string $type Content type
     * @return void        N/a
     */
    public function setContentType( string $type ) : void
    {
        $this->headers->set( 'Content-Type', $type );
    }

    /**
     * Sends this HTTP response to the client
     *
     * @return void N/a
     */
    public function send() : void
    {
        \http_response_code( $this->status );

        foreach ( $this->headers->all() as $name => $values ) {
            foreach ( $values as $index => $value ) {
                \header( "$name: $value", $index === 0 );
            }
        }

        echo $this->content;
    }
}
