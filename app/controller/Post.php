<?php

namespace App\Controller;

use Swiftly\Base\AbstractController;
use Swiftly\Http\Server\Response;
use App\Model\PostProvider;
use Swiftly\Routing\UrlGenerator;

class Post extends AbstractController
{
    public function view(
        PostProvider $provider,
        UrlGenerator $url,
        string $slug
    ): ?Response {
        $post = $provider->getPost($slug);

        if (!$post) {
            return new Response('404 - Not found', 404);
        }

        return $this->output('post.html.php', [
            'title'       => $post['title'],
            'author'      => $post['author'],
            'author_slug' => $post['author_slug'],
            'published'   => $post['published'],
            'content'     => 'TODO'
        ]);
    }
}
