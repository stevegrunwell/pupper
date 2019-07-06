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
            'user'
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
}