<?php

namespace App\Model;

use App\Model\Post;

use function array_filter;
use function array_slice;

/**
 * Utility used to fetch post information
 */
class PostProvider
{
    private $posts;

    public function __construct($provider = null)
    {
        $this->posts = [
            'getting-started-typescript' => Post::fromArray([
                'title'       => 'Getting started with TypeScript',
                'slug'        => 'getting-started-typescript',
                'author'      => 'Matt Pocock',
                'author_slug' => 'matt-pocock',
                'published'   => '2023-01-23T12:50:07'
            ]),
            'introduction-php-8' => Post::fromArray([
                'title'       => 'Introduction to PHP 8',
                'slug'        => 'introduction-php-8',
                'author'      => 'Brent Roose',
                'author_slug' => 'brent-roose',
                'published'   => '2023-02-09T09:12:46'
            ]),
            'performance-tips-nginx' => Post::fromArray([
                'title'       => 'Performance tips for Nginx',
                'slug'        => 'performance-tips-nginx',
                'author'      => 'Plesk Support',
                'author_slug' => 'plesk-support',
                'published'   => '2023-02-17T11:32:10'
            ]),
            'migrating-gulp-webpack' => Post::fromArray([
                'title'       => 'Migrating from Gulp to WebPack',
                'slug'        => 'migrating-gulp-webpack',
                'author'      => 'Conor Varley',
                'author_slug' => 'conor-varley',
                'published'   => '2023-03-21T17:40:18'
            ]),
            'logical-properties-css' => Post::fromArray([
                'title'       => 'Logical properties in CSS',
                'slug'        => 'logical-properties-css',
                'author'      => 'Michelle Barker',
                'author_slug' => 'michelle-barker',
                'published'   => '2023-04-01T13:29:54'
            ]),
            'future-wordpress' => Post::fromArray([
                'title'       => 'The future of WordPress',
                'slug'        => 'future-wordpress',
                'author'      => 'Matt Mullenweg',
                'author_slug' => 'matt-mullenweg',
                'published'   => '2023-06-28T08:07:43'
            ])
        ];
    }

    /**
     * Return a collection of posts from the database
     * 
     * The second optional `$query` argument takes the following values:
     * * `page` - Pagination offset
     * * `year` - Filter posts by year
     * * `month` - Filter posts by month
     * 
     * 
     * @param array $query Query options
     * @return Post[]
     */
    function getPosts(array $query = []): array
    {
        $page_size = 4;
        $page_number = !empty($query['page']) ? (int)$query['page'] : 1;

        $posts = $this->posts;

        if (!empty($query['year']) && !empty($query['month'])) {
            $year = (int)$query['year'];
            $month = (int)$query['month'];

            $posts = array_filter(
                $posts,
                function (array $post) use ($year, $month): bool {
                    return $post['published']->format("n") == $month;
                }
            );
        }

        if (!empty($query['author'])) {
            $author = (string)$query['author'];

            $posts = array_filter(
                $posts,
                function (array $post) use ($author): bool {
                    return $post['author_slug'] === $author;
                }
            );
        }

        usleep(250000);

        return array_slice($posts, ($page_number - 1) * $page_size, $page_size);
    }

    /**
     * Return a single post by slug
     * 
     * @param string $slug Post slug
     * @return Post|null
     */
    function getPost(string $slug): ?Post
    {
        if (!isset($this->posts[$slug])) {
            return null;
        }

        usleep(125000);

        return $this->posts[$slug];
    }
}
