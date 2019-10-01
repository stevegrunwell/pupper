<?php

namespace App\Contracts;

use App\Post;
use Illuminate\Support\Collection;

interface PostParserContract
{
    public function __construct(Post $post);

    /**
     * Retrieve an array of all usernames mentioned in the post.
     */
    public function getUsernames(): array;
}
