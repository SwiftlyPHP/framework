<?php

namespace Swiftly\Http\Server;

use \Swiftly\Http\Headers;

/**
 * Class used to redirect the client to a new resource
 *
 * @author clvarley
 */
Class RedirectResponse Extends Response
{

    /**
     * Location the client is to be redirected to
     *
     * @var string $location Redirect destination
     */
    protected $location;

    /**
     * Creates a new redirect toward the given location
     *
     * @param string $location Redirect destination
     * @param int $status      (Optional) Status code
     * @param array $headers   (Optional) Http headers
     */
    public function __construct( string $location, int $status = 303, array $headers = [] )
    {
        $this->location = $location;

        parent::__construct( '', $status, $headers );
    }

    /**
     * @inheritdoc
     */
    public function send() : void
    {
        $this->headers->set( "Location", $this->location );

        // Not required, but good practice
        if ( empty( $this->content ) ) {
            $this->content = "Redirecting to: {$this->location}";
        }

        parent::send();
    }
}
