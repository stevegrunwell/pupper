<?php

namespace App\Services;

use App\Contracts\PostParserContract;
use App\Post;

class PostParser implements PostParserContract
{
    /**
     * @var \App\Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Retrieve an array of all usernames mentioned in the post.
     */
    public function getUsernames(): array
    {
        if (! preg_match_all('/(?:\s|^)@([A-Z0-9_]{4,})/i', $this->post->content, $matches)) {
            return [];
        }

        return $matches[1];
    }
}
