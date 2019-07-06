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
     * @group Relationships
     */
    public function a_user_can_follow_another_user()
    {
        $users = factory(User::class, 2)->create();

        $this->actingAs($users[0])
            ->post(route('users.follow', ['user' => $users[1]]))
            ->assertRedirect(route('users.show', ['user' => $users[1]]));

        $this->assertCount(1, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function nothing_happens_when_following_an_already_followed_user()
    {
        $users = factory(User::class, 2)->create();
        $users[1]->followers()->save($users[0]);

        $this->actingAs($users[0])
            ->post(route('users.follow', ['user' => $users[1]]))
            ->assertRedirect(route('users.show', ['user' => $users[1]]));

        $this->assertCount(1, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function a_user_can_unfollow_another_user()
    {
        $users = factory(User::class, 2)->create();
        $users[1]->followers()->save($users[0]);

        $this->actingAs($users[0])
            ->delete(route('users.follow', ['user' => $users[1]]))
            ->assertRedirect(route('users.show', ['user' => $users[1]]));

        $this->assertCount(0, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function nothing_happens_when_unfollowing_an_already_unfollowed_user()
    {
        $users = factory(User::class, 2)->create();

        $this->actingAs($users[0])
            ->delete(route('users.follow', ['user' => $users[1]]))
            ->assertRedirect(route('users.show', ['user' => $users[1]]));

        $this->assertCount(0, $users[0]->followers);
    }
}
