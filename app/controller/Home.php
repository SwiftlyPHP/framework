<?php

use Swiftly\Base\Controller;
use Swiftly\Http\Server\Response;

/**
 * The default controller that handles the homepage
 *
 * @author clvarley
 */
Class Home Extends Controller
{

    /**
     * Load the homepage template
     *
     * @return Response HTTP response
     */
    public function index() : Response
    {
        // Output a response
        return $this->output( 'home', [
            'title'   => 'Swiftly',
            'message' => 'Thanks for installing Swiftly!'
        ]);
    }

    /**
     * Says hello to the given name
     *
     * @param string $name User name
     * @return Response    HTTP response
     */
    public function hello( string $name ) : Response
    {
        // Pass the named paramater in
        return $this->output( 'home', [
            'title'   => ucfirst( $name ),
            'message' => 'Welcome to Swiftly!'
        ]);
    }
}
