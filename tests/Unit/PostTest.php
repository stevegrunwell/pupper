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

    /**
     * @test
     * @group Timeline
     * @covers App\Post::scopeFromUsers()
     */
    public function posts_can_be_filtered_by_users()
    {
        $users = factory(User::class, 3)->create()->each(function($user) {
            $user->posts()->saveMany(factory(Post::class, 3)->make());
        });
        $users->pop();

        $postIds  = Post::fromUsers($users)->get()->pluck('id');
        $expected = $users->load('posts')->pluck('posts.*.id')->flatten();

        $this->assertCount(6, $postIds);
        $this->assertEquals($expected, $postIds);
    }
}
