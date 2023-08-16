<?php

namespace App\Controller;

/**
 * Controller for rending the homepage
 * 
 * @example Basic shape of a controller
 */
class Home
{
    /**
     * Load the homepage template
     */
    public function index(): void
    {
        $title = 'Homepage';

        require APP_VIEW . '/example-home.html.php';
        die;
    }
}
