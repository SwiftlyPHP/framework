<?php

namespace App\Model;

use AllowDynamicProperties;
use DateTimeImmutable;

#[AllowDynamicProperties]
final class Post
{
    /** @var string $title Post title */
    public $title;

    /** @var string $slug Post URL slug */
    public $slug;

    /** @var string $author Author name */
    public $author;

    /** @var string $author_slug Author slug */
    public $author_slug;

    /** @var DateTimeImmutable $published Post publication date */
    public $published;

    /** @var string $content Post content */
    public $content = '<p>TODO</p>';

    /** @var string $link Permalink */
    public $link = '#';

    /**
     * Generate a Post object from an array of data
     * 
     * @param array{
     *  title:string,
     *  slug:string,
     *  author:string,
     *  author_slug:string,
     *  published:string,
     *  content:?string
     * } $data
     */
    public static function fromArray(array $data): Post
    {
        $post = new Post();
        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->author = $data['author'];
        $post->author_slug = $data['author_slug'];
        $post->published = new DateTimeImmutable($data['published']);
        $post->content = $data['content'] ?? $post->content;

        return $post;
    }
}
