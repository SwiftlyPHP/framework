<?php

namespace App\Controller;

use Swiftly\Base\AbstractController;
use Swiftly\Http\Server\Response;
use App\Model\PostProvider;
use Swiftly\Routing\UrlGenerator;

class Post extends AbstractController
{
    public function view(PostProvider $provider, UrlGenerator $url, string $slug): ?Response
    {
        $post = $provider->getPost($slug);

        if (!$post) {
            $response = $this->output('404.html.php', ['title' => '404']);
            $response->setStatus(404);
            return $response;
        }

        return $this->output('post.html.php', [
            'title'       => $post->title,
            'author'      => $post->author,
            'author_slug' => $post->author_slug,
            'published'   => $post->published,
            'content'     => $post->content
        ]);
    }
}
