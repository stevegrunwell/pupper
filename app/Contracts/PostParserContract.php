<?php

namespace App\Contracts;

use App\Post;

interface PostParserContract
{
    public function __construct(Post $post);

    /**
     * Retrieve an array of all usernames mentioned in the post.
     */
    public function getUsernames(): array;
}
