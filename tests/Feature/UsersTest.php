<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Users
 */
class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function anyone_can_see_a_user_profile()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('users.show', ['user' => $user]));

        $response->assertViewIs('users.show');
        $response->assertViewHas([
            'posts',
            'user',
        ]);
    }

    /**
     * @test
     */
    public function a_user_profile_should_return_the_posts_count()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('users.show', ['user' => $user]));

        $this->assertArrayHasKey('posts_count', $response->original->user->toArray());
    }

    /**
     * @test
     */
    public function a_404_is_returned_if_no_matching_user_was_found()
    {
        $this->get(route('users.show', ['user' => 'some-missing-user']))
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function user_routes_should_be_based_on_username_instead_of_id()
    {
        $user  = factory(User::class)->create();
        $route = route('users.show', ['user' => $user]);

        $this->assertStringContainsString($user->username, $route);
        $this->assertStringNotContainsString($user->id, $route);
    }

    /**
     * @test
     */
    public function it_should_display_the_accounts_a_user_is_following()
    {
        $user = factory(User::class)->create();
        $following = $user->following()->saveMany(factory(User::class, 3)->make());

        $response = $this->get(route('users.following', ['user' => $user]));

        $response->assertViewIs('users.following');
        $this->assertCount(3, $response->original->users);
    }

    /**
     * @test
     */
    public function it_should_display_the_accounts_following_the_user()
    {
        $user = factory(User::class)->create();
        $following = $user->followers()->saveMany(factory(User::class, 3)->make());

        $response = $this->get(route('users.followers', ['user' => $user]));

        $response->assertViewIs('users.followers');
        $this->assertCount(3, $response->original->users);
    }
}
