<?php

namespace Tests\Feature;

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
     * @test
     */
    public function a_logged_in_user_can_see_the_form_to_create_a_post()
    {
        $this->actingAs(factory(User::class)->make())
            ->get(route('posts.create'))
            ->assertViewIs('posts.create');
    }

    /**
     * @test
     */
    public function a_guest_should_be_redirected_to_the_login_screen_if_they_try_to_create_a_post()
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function a_user_can_create_a_new_post()
    {
        $post = factory(Post::class)->make();

        $response = $this->actingAs($post->user)
            ->post(route('posts.store'), [
                'content' => $post->content,
            ]);

        $post->user->refresh();

        $this->assertCount(1, $post->user->posts);

        $response->assertRedirect(route('posts.show', ['post' => $post->user->posts->first()]));
    }

    /**
     * @test
     */
    public function a_user_can_see_a_post()
    {
        $post = factory(Post::class)->create();

        $response = $this->get(route('posts.show', ['post' => $post]));

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
        $this->assertTrue($response->original['post']->is($post));
    }
}
