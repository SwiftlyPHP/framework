<?php

use Swiftly\Core\Controller;
use Swiftly\Http\Response\Response;

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
        return $this->render('home.html.php', [
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
    public function hello(string $name): Response
    {
        return $this->render('home.html.php', [
            'title'   => 'Hi ' . ucfirst($name),
            'message' => 'Welcome to Swiftly!'
        ]);
    }
}
