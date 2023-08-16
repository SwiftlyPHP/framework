<?php

namespace App\Controller;

use Swiftly\Base\AbstractController;
use Swiftly\Http\Server\Response;
use App\Model\PostProvider;

use function count;

/**
 * Archive for displaying a list of blog posts
 * 
 * @package Posts
 */
class Archive extends AbstractController
{
    /**
     * Display a lists of posts
     * 
     * @param int $page Requested pagination offset
     */
    public function view(PostProvider $provider, int $page = 1): Response
    {
        $posts = $provider->getPosts(['page' => $page]);

        return $this->output('archive.html.php', [
            'count' => count($posts),
            'posts' => $posts
        ]);
    }

    /**
     * Display a list of posts filtered by year and month
     * 
     * @param int $year  Publication year
     * @param int $month Publication month
     */
    public function viewByDate(PostProvider $provider, int $year, int $month): Response
    {
        $posts = $provider->getPosts([
            'year'  => $year,
            'month' => $month
        ]);

        return $this->output('archive.html.php', [
            'count' => count($posts),
            'posts' => $posts
        ]);
    }

    /**
     * Display a list of posts written by a particlar author
     * 
     * @param string $author Author slug
     */
    public function viewByAuthor(PostProvider $provider, string $author): Response
    {
        $posts = $provider->getPosts(['author' => $author]);

        return $this->output('archive.html.php', [
            'count' => count($posts),
            'posts' => $posts
        ]);
    }
}
