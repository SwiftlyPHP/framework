<?php

namespace App\Controller;

use Swiftly\Base\AbstractController;
use Swiftly\Dependency\Container;
use Swiftly\Template\TemplateInterface;
use Swiftly\Routing\UrlGenerator;
use Swiftly\Http\Server\Response;
use App\Model\PostProvider;
use App\Model\Post;

use function count;

/**
 * Archive for displaying a list of blog posts
 * 
 * @package Posts
 */
class Archive extends AbstractController
{
    /** @var PostProvider $provider */
    private $provider;
    /** @var UrlGenerator $url */
    private $url;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Container $container,
        TemplateInterface $template,
        PostProvider $provider,
        UrlGenerator $url
    ) {
        parent::__construct($container, $template);
        $this->provider = $provider;
        $this->url = $url;
    }

    /**
     * Display a lists of posts
     * 
     * @param int $page Requested pagination offset
     */
    public function view(int $page = 1): Response
    {
        $posts = $this->getPosts(['page' => $page]);

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
    public function viewByDate(int $year, int $month): Response
    {
        $posts = $this->getPosts([
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
    public function viewByAuthor(string $author): Response
    {
        $posts = $this->getPosts(['author' => $author]);

        return $this->output('archive.html.php', [
            'count' => count($posts),
            'posts' => $posts
        ]);
    }

    /**
     * Query for posts and returned them correctly linked
     * 
     * @param array $query Post query
     * @return Post[]      Retrieved posts
     */
    private function getPosts(array $query): array
    {
        $posts = $this->provider->getPosts($query);

        foreach ($posts as $post) {
            $post->link = $this->url->generate('post', ['slug' => $post->slug]);
        }

        return $posts;
    }
}
