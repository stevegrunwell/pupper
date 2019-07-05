<?php

namespace Tests\Unit;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Posts
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group Users
     */
    public function a_post_belongs_to_a_user()
    {
        $post = factory(Post::class)->make();

        $this->assertInstanceOf(User::class, $post->user);
    }
}
