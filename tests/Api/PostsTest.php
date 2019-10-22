<?php

namespace Tests\Api;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Posts
 */
class PostsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function a_user_should_be_able_to_create_a_new_post()
    {
        $response = $this->actingAs($this->user, 'api')
            ->post(route('api.posts.store'), [
                'content' => 'Some post content',
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'id' => Post::latest()->first()->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_should_be_able_to_retrieve_posts()
    {
        $post = factory(Post::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->get(route('api.posts.show', ['post' => $post]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_should_be_able_to_delete_posts()
    {
        $post = factory(Post::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->delete(route('api.posts.show', ['post' => $post]))
            ->assertStatus(204);

        $post->refresh();

        $this->assertTrue($post->trashed());
    }

    /**
     * @test
     */
    public function a_user_can_reply_to_another_post()
    {
        $parent = factory(Post::class)->create();
        $user   = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('api.posts.store'), [
                'parent_id' => $parent->id,
                'content'   => 'George Harrison',
            ]);

        $parent->refresh();

        $this->assertCount(1, $parent->replies);
        $this->assertSame('George Harrison', $parent->replies[0]->content);
    }
}
