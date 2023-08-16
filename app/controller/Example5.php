<?php

namespace App\Controller;

use Swiftly\Base\AbstractController;
use Swiftly\Http\Server\Response;
use App\Model\PostProvider;

use function count;

/**
 * Class used to represent an archive of posts
 * 
 * @example Returning non-HTML responses
 */
class Example5 extends AbstractController
{
    /**
     * View a list of posts, paginating as required
     */
    public function view(PostProvider $provider, int $page = 1): Response
    {
        $posts = $provider->getPosts(['page' => $page]);

        // Render template file
        return $this->output('archive.html.php', [
            'title' => 'Search results',
            'count' => count($posts),
            'posts' => $provider->getPosts(['page' => $page])
        ]);
    }
}
