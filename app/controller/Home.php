<?php

namespace App\Controller;

use Swiftly\Core\Controller;
use Swiftly\Http\Response\Response;

use function ucfirst;

/**
 * Example controller that returns HTML responses.
 */
class Home extends Controller
{

    /**
     * Load a simple php template
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
     * Example of route matching with variable component
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
