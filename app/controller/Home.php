<?php

use Swiftly\Base\AbstractController;
use Swiftly\Http\Server\Response;

/**
 * The default controller that handles the homepage
 *
 * @author clvarley
 */
Class Home Extends AbstractController
{

    /**
     * Load the homepage template
     *
     * @return Response HTTP response
     */
    public function index() : Response
    {
        // Output a response
        return $this->output( 'home.html.php', [
            'title'   => 'Welcome',
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
        return $this->output( 'home.html.php', [
            'title'   => 'Hi ' . ucfirst( $name ),
            'message' => 'Welcome to Swiftly!'
        ]);
    }
}
