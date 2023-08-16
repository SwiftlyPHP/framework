<?php

namespace App\Controller;

use App\Model\PostProvider;

use function count;

/**
 * Class used to represent an archive of posts
 * 
 * @example Extending AbstractController, rendering templates and returning responses
 */
class Example4
{
    /**
     * View a list of posts, paginating as required
     */
    public function view(PostProvider $provider, int $page = 1): void
    {
        // @example TODO

        $posts = $provider->getPosts(['page' => $page]);
        $count = count($posts);
        $title = "Search results";

        // Load template
        require APP_VIEW . '/example-archive.html.php';
        exit(0);
    }
}
